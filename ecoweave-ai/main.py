from http.server import BaseHTTPRequestHandler, HTTPServer
import json
import pymysql

# Konfigurasi koneksi MySQL
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'ecoweave_db',
    'cursorclass': pymysql.cursors.DictCursor
}

def get_recommendations(user_id, limit=6):
    conn = pymysql.connect(**DB_CONFIG)
    try:
        with conn.cursor() as cursor:
            # 1. Ambil histori interaksi pengguna
            cursor.execute("""
                SELECT item_id, item_type, interaction_type, rating_value 
                FROM interactions WHERE user_id = %s
            """, (user_id,))
            interactions = cursor.fetchall()

            # 2. Ambil preferensi onboarding (Cold Start Handler)
            cursor.execute("SELECT category_id FROM user_preferences WHERE user_id = %s", (user_id,))
            prefs = [row['category_id'] for row in cursor.fetchall()]

            # 3. Hitung bobot interaksi murni dengan Python
            weight_map = {'click': 1, 'like': 3, 'purchase': 5}
            product_scores = {}

            for row in interactions:
                if row['item_type'] == 'product':
                    score = float(row['rating_value']) if (row['interaction_type'] == 'rating' and row['rating_value']) else weight_map.get(row['interaction_type'], 1)
                    item_id = row['item_id']
                    product_scores[item_id] = product_scores.get(item_id, 0) + score

            # Ambil kategori dari 5 produk dengan skor tertinggi
            if product_scores:
                top_ids = sorted(product_scores, key=product_scores.get, reverse=True)[:5]
                format_strings = ','.join(['%s'] * len(top_ids))
                cursor.execute(f"SELECT DISTINCT category_id FROM products WHERE id IN ({format_strings})", tuple(top_ids))
                inter_cats = [row['category_id'] for row in cursor.fetchall()]
                prefs = list(set(prefs + inter_cats))

            recommended_products = []
            recommended_articles = []

            # 4. Tarik ID produk dan artikel berdasarkan gabungan minat
            if prefs:
                cat_placeholders = ','.join(['%s'] * len(prefs))
                
                cursor.execute(f"""
                    SELECT p.id FROM products p
                    LEFT JOIN interactions i ON p.id = i.item_id AND i.item_type = 'product'
                    WHERE p.category_id IN ({cat_placeholders}) AND p.stock > 0
                    GROUP BY p.id
                    ORDER BY COUNT(i.id) DESC, p.created_at DESC
                    LIMIT %s
                """, tuple(prefs) + (limit,))
                recommended_products = [row['id'] for row in cursor.fetchall()]

                cursor.execute(f"""
                    SELECT a.id FROM articles a
                    LEFT JOIN interactions i ON a.id = i.item_id AND i.item_type = 'article'
                    WHERE a.category_id IN ({cat_placeholders})
                    GROUP BY a.id
                    ORDER BY COUNT(i.id) DESC, a.created_at DESC
                    LIMIT %s
                """, tuple(prefs) + (limit,))
                recommended_articles = [row['id'] for row in cursor.fetchall()]

            return {
                "status": "success",
                "user_id": user_id,
                "product_ids": recommended_products,
                "article_ids": recommended_articles
            }
    finally:
        conn.close()

class AIRequestHandler(BaseHTTPRequestHandler):
    def do_GET(self):
        # Endpoint: /api/recommend/{user_id}
        if self.path.startswith("/api/recommend/"):
            try:
                user_id = int(self.path.split("/")[-1])
                result = get_recommendations(user_id)
                
                self.send_response(200)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                self.wfile.write(json.dumps(result).encode('utf-8'))
            except Exception as e:
                self.send_response(500)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                self.wfile.write(json.dumps({"status": "error", "message": str(e)}).encode('utf-8'))
        else:
            self.send_response(404)
            self.end_headers()

if __name__ == "__main__":
    server = HTTPServer(('127.0.0.1', 8001), AIRequestHandler)
    print("EcoWeave AI Service berjalan di http://127.0.0.1:8001 ...")
    server.serve_forever()