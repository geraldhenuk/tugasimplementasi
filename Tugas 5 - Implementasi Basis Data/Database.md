## CREATE TABLE
-- 1. Tabel user
CREATE TABLE user (
user_id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
phone VARCHAR(20),
role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);   

-- 2. Tabel category
CREATE TABLE category (
category_id INT PRIMARY KEY AUTO_INCREMENT,
category_name VARCHAR(100) NOT NULL
);   

-- 3. Tabel product
CREATE TABLE product (
product_id INT PRIMARY KEY AUTO_INCREMENT,
product_name VARCHAR(150) NOT NULL,
price DOUBLE NOT NULL,
stock INT NOT NULL DEFAULT 0,
description TEXT,
eco_label VARCHAR(100),
eco_impact VARCHAR(255),
verification_status ENUM('pending','verified','rejected') DEFAULT 'pending',
image_url VARCHAR(255),
category_id INT,
admin_id INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (category_id) REFERENCES category(category_id),
FOREIGN KEY (admin_id) REFERENCES user(user_id)
);   

-- 4. Tabel cart
CREATE TABLE cart (
cart_id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL,
FOREIGN KEY (user_id) REFERENCES user(user_id)
);   

-- 5. Tabel cart_item
CREATE TABLE cart_item (
cart_item_id INT PRIMARY KEY AUTO_INCREMENT,
cart_id INT NOT NULL,
product_id INT NOT NULL,
quantity INT NOT NULL DEFAULT 1,
FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
FOREIGN KEY (product_id) REFERENCES product(product_id)
);   

-- 6. Tabel transaction
CREATE TABLE transaction (
transaction_id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL,
total_price DOUBLE NOT NULL DEFAULT 0,
transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
status ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
FOREIGN KEY (user_id) REFERENCES user(user_id)
);  

-- 7. Tabel transaction_item
CREATE TABLE transaction_item (
transaction_item_id INT PRIMARY KEY AUTO_INCREMENT,
transaction_id INT NOT NULL,
product_id INT NOT NULL,
quantity INT NOT NULL DEFAULT 1,
price_at_purchase DOUBLE NOT NULL,
FOREIGN KEY (transaction_id) REFERENCES transaction(transaction_id),
FOREIGN KEY (product_id) REFERENCES product(product_id)
);   

-- 8. Tabel payment
CREATE TABLE payment (
payment_id INT PRIMARY KEY AUTO_INCREMENT,
transaction_id INT NOT NULL UNIQUE,
payment_method VARCHAR(50) NOT NULL,
payment_status ENUM('pending','success','failed') DEFAULT 'pending',
payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
amount DOUBLE NOT NULL,
FOREIGN KEY (transaction_id) REFERENCES transaction(transaction_id)
);   

-- 9. Tabel review
CREATE TABLE review (
review_id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL,
product_id INT NOT NULL,
rating INT NOT NULL,
comment TEXT,
review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES user(user_id),
FOREIGN KEY (product_id) REFERENCES product(product_id)
);   

- 10. Tabel education_content
CREATE TABLE education_content (
content_id INT PRIMARY KEY AUTO_INCREMENT,
admin_id INT,
title VARCHAR(200) NOT NULL,
content TEXT NOT NULL,
published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (admin_id) REFERENCES user(user_id)
);   

## INSERT INTO
-- Insert 5 data ke tabel user
INSERT INTO user (user_id, name, email, password, phone, role, created_at) VALUES
(1, 'Admin EcoWeave', 'admin@ecoweave.com', 'admin123', '081234567890', 'admin', '2026-06-19 04:00:14'),
(2, 'Annisa Nur Shadrina', 'annisa@gmail.com', 'customer123', '081111111111', 'customer', '2026-06-19 04:00:14'),
(3, 'Fadia Nabila', 'fadia@gmail.com', 'customer123', '081222222222', 'customer', '2026-06-19 04:00:14'),
(4, 'Joo', 'joo@gmail.com', '123', '08123333333', 'customer', '2026-06-19 06:54:55'),
(5, 'Gerald', 'gerald@gmail.com', '17oktober', '0812344444', 'customer', '2026-06-23 15:25:40');   

-- Insert 5 data ke tabel category
INSERT INTO category (category_id, category_name) VALUES
(1, 'Tanaman Hias'),
(2, 'Dalam Ruangan'),
(3, 'Luar Ruangan'),
(4, 'Perabot Ramah Lingkungan'),
(5, 'Produk Daur Ulang');   

-- Insert 5 data ke tabel product
INSERT INTO product (product_id, product_name, price, stock, description, eco_label, eco_impact, verification_status, image_url, category_id, admin_id, created_at) VALUES
(1, 'Mammillaria Bloom', 250000, 15, 'Kaktus hias cantik cocok untuk meja kerja, perawatan mudah.', 'Biodegradable', 'Membantu menyerap CO2 dan menyaring udara dalam ruangan.', 'verified', '4090ba54-0f47-4336-adda-dba7ab0ddc31.webp', 1, 1, '2026-06-19 04:00:14'),
(2, 'Strelitzia', 250000, 15, 'Tanaman hias tropis dengan daun besar nan eksotis.', 'Pestisida Nabati', 'Meningkatkan kualitas udara dan kelembapan ruangan.', 'verified', '7dc5ddba-52f1-4411-9cdc-bb8e9eae4a41.jpg', 2, 1, '2026-06-19 04:00:14'),
(3, 'Philodendron', 250000, 15, 'Tanaman daun hijau yang mudah dirawat dan tahan banting.', 'Organik', 'Membantu mengurangi polutan udara di ruangan tertutup.', 'verified', '50ae1335-7ebf-4058-ae73-2632db7ff680.webp', 2, 1, '2026-06-19 04:00:14'),
(4, 'Phalaenopsis Amabilis', 250000, 20, 'Daun tebal hijau mengilap, bertipe monopodial dengan akar udara perak yang aktif menyerap kelembaban.', 'Orchidaechae', 'Menghias Ruangan', 'verified', '2d21d4f6-7e14-4c50-ac66-2c01b04a31b4.webp', 1, 1, '2026-06-23 15:45:13'),
(5, 'Agave attenuate', 300000, 18, 'Tumbuhan sukulen berbentuk perdu kecil dari famili Asparagaceae.', 'Biodegradable', 'Penghias Ruangan', 'verified', '36846e1d-0f7f-4f21-a21c-0185cd4e6b61.jpg', 3, 1, '2026-06-23 15:46:48');   

-- Insert 5 data ke tabel cart (3 data pertama dari file dump, ditambah 2 data pelengkap)
INSERT INTO cart (cart_id, user_id) VALUES
(1, 2),
(2, 3),
(3, 5),
(4, 4),
(5, 1);   

-- Insert 5 data ke tabel cart_item (data pelengkap karena tabel di file dump masih kosong)
INSERT INTO cart_item (cart_item_id, cart_id, product_id, quantity) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 1),
(4, 3, 4, 3),
(5, 4, 5, 1);   

-- Insert 5 data ke tabel transaction
INSERT INTO transaction (transaction_id, user_id, total_price, transaction_date, status) VALUES
(1, 2, 50000, '2026-06-19 13:50:53', 'paid'),
(2, 3, 750000, '2026-06-19 14:37:48', 'paid'),
(3, 2, 500000, '2026-06-19 14:45:37', 'paid'),
(4, 3, 600000, '2026-06-24 11:26:47', 'paid'),
(5, 5, 250000, '2026-06-24 22:34:28', 'paid');   

-- Insert 5 data ke tabel transaction_item (disesuaikan dengan ID produk 1-5)
INSERT INTO transaction_item (transaction_item_id, transaction_id, product_id, quantity, price_at_purchase) VALUES
(1, 1, 1, 1, 250000),
(2, 2, 1, 3, 250000),
(3, 3, 1, 2, 250000),
(4, 4, 5, 2, 300000),
(5, 5, 3, 1, 250000);   

-- Insert 5 data ke tabel payment
INSERT INTO payment (payment_id, transaction_id, payment_method, payment_status, payment_date, amount) VALUES
(1, 1, 'COD', 'success', '2026-06-19 13:50:53', 50000),
(2, 2, 'COD', 'success', '2026-06-19 14:37:48', 750000),
(3, 3, 'COD', 'success', '2026-06-19 14:45:37', 500000),
(4, 4, 'E-Wallet', 'success', '2026-06-24 11:26:47', 600000),
(5, 5, 'COD', 'success', '2026-06-24 22:34:28', 250000);   

-- Insert 5 data ke tabel review (menggunakan data dari file dump ditambah data pelengkap agar berjumlah 5)
INSERT INTO review (review_id, user_id, product_id, rating, comment, review_date) VALUES
(1, 2, 1, 5, 'Aisad', '2026-06-19 14:45:06'),
(2, 3, 3, 5, 'Bagus', '2026-06-24 23:19:49'),
(3, 4, 2, 4, 'Tanaman segar dan pengiriman cepat', '2026-06-25 10:00:00'),
(4, 5, 4, 5, 'Sangat cocok untuk dekorasi meja', '2026-06-25 11:30:00'),
(5, 2, 5, 4, 'Kualitas tanaman sangat baik', '2026-06-25 13:15:00');   

-- Insert 5 data ke tabel education_content
INSERT INTO education_content (content_id, admin_id, title, content, published_at) VALUES
(1, 1, 'Mengapa Produk Ramah Lingkungan Penting?', 'Produk ramah lingkungan membantu mengurangi jejak karbon dan sampah yang dihasilkan dari aktivitas konsumsi sehari-hari.', '2026-06-19 11:00:14'),
(2, 1, 'Tips Memilih Tanaman Hias untuk Kualitas Udara', 'Beberapa tanaman seperti Philodendron dan Lidah Mertua diketahui efektif menyaring udara dalam ruangan dan meningkatkan kadar oksigen di rumah.', '2026-06-19 11:00:14'),
(3, 1, 'Pengolahan Tanah yang Baik', 'Pengolahan tanah adalah langkah pertama dan paling penting dalam bertani.', '2026-06-23 22:48:36'),
(4, 1, 'Penggunaan Pupuk Organik', 'Pupuk organik menjadi pilihan yang lebih sehat dan ramah lingkungan dibandingkan dengan pupuk kimia.', '2026-06-23 22:49:06'),
(5, 1, 'Teknik Irigasi Efisien', 'Irigasi yang efisien adalah kunci untuk mendapatkan hasil panen yang maksimal.', '2026-06-23 22:49:25');   
