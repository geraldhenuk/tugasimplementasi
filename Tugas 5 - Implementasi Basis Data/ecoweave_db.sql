-- =========================================================
-- DATABASE ECOWEAVE
-- Marketplace Ramah Lingkungan Berbasis Prinsip SDGs
-- Sesuai ER Diagram pada Laporan TUBES PBO
-- =========================================================

DROP DATABASE IF EXISTS ecoweave_db;
CREATE DATABASE ecoweave_db;
USE ecoweave_db;

-- =========================================================
-- TABEL USER
-- Menyimpan data umum pengguna (Customer & Admin)
-- role membedakan apakah dia 'customer' atau 'admin'
-- =========================================================
CREATE TABLE user (
    user_id     INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    phone       VARCHAR(20),
    role        ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- TABEL CATEGORY
-- Mengelompokkan produk
-- =========================================================
CREATE TABLE category (
    category_id     INT AUTO_INCREMENT PRIMARY KEY,
    category_name   VARCHAR(100) NOT NULL
);

-- =========================================================
-- TABEL PRODUCT
-- Menyimpan data produk ramah lingkungan
-- =========================================================
CREATE TABLE product (
    product_id          INT AUTO_INCREMENT PRIMARY KEY,
    product_name        VARCHAR(150) NOT NULL,
    price               DOUBLE NOT NULL,
    stock               INT NOT NULL DEFAULT 0,
    description         TEXT,
    eco_label           VARCHAR(100),          -- contoh: "Biodegradable", "Daur Ulang"
    eco_impact          VARCHAR(255),           -- penjelasan dampak lingkungan
    verification_status ENUM('pending','verified','rejected') DEFAULT 'pending',
    image_url           VARCHAR(255),
    category_id         INT,
    admin_id            INT,                    -- admin/seller yang menambahkan produk
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category(category_id) ON DELETE SET NULL,
    FOREIGN KEY (admin_id) REFERENCES user(user_id) ON DELETE SET NULL
);

-- =========================================================
-- TABEL CART
-- Keranjang belanja milik customer
-- =========================================================
CREATE TABLE cart (
    cart_id     INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- =========================================================
-- TABEL CART_ITEM
-- Detail produk di dalam cart (relasi Cart - Product, many to many)
-- =========================================================
CREATE TABLE cart_item (
    cart_item_id    INT AUTO_INCREMENT PRIMARY KEY,
    cart_id         INT NOT NULL,
    product_id      INT NOT NULL,
    quantity        INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cart_id) REFERENCES cart(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE
);

-- =========================================================
-- TABEL TRANSACTION
-- Mencatat transaksi pembelian customer
-- =========================================================
CREATE TABLE transaction (
    transaction_id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id              INT NOT NULL,
    total_price          DOUBLE NOT NULL DEFAULT 0,
    transaction_date     DATETIME DEFAULT CURRENT_TIMESTAMP,
    status                ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- =========================================================
-- TABEL TRANSACTION_ITEM
-- Detail produk yang dibeli pada satu transaksi
-- =========================================================
CREATE TABLE transaction_item (
    transaction_item_id  INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id        INT NOT NULL,
    product_id            INT NOT NULL,
    quantity              INT NOT NULL DEFAULT 1,
    price_at_purchase     DOUBLE NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transaction(transaction_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE
);

-- =========================================================
-- TABEL PAYMENT
-- Pembayaran terkait satu transaksi (composition 1..1)
-- =========================================================
CREATE TABLE payment (
    payment_id      INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id   INT NOT NULL UNIQUE,
    payment_method   VARCHAR(50) NOT NULL,
    payment_status   ENUM('pending','success','failed') DEFAULT 'pending',
    payment_date     DATETIME DEFAULT CURRENT_TIMESTAMP,
    amount           DOUBLE NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transaction(transaction_id) ON DELETE CASCADE
);

-- =========================================================
-- TABEL REVIEW
-- Penilaian customer terhadap produk
-- =========================================================
CREATE TABLE review (
    review_id    INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    product_id   INT NOT NULL,
    rating       INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment      TEXT,
    review_date  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON DELETE CASCADE
);

-- =========================================================
-- TABEL EDUCATION_CONTENT
-- Konten edukasi lingkungan, dibuat oleh Admin
-- =========================================================
CREATE TABLE education_content (
    content_id    INT AUTO_INCREMENT PRIMARY KEY,
    admin_id      INT,
    title         VARCHAR(200) NOT NULL,
    content       TEXT NOT NULL,
    published_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES user(user_id) ON DELETE SET NULL
);

-- =========================================================
-- DATA AWAL (SEED DATA)
-- =========================================================

-- Admin default (password: admin123)
INSERT INTO user (name, email, password, phone, role) VALUES
('Admin EcoWeave', 'admin@ecoweave.com', 'admin123', '081234567890', 'admin');

-- Customer contoh (password: customer123)
INSERT INTO user (name, email, password, phone, role) VALUES
('Annisa Nur Shadrina', 'annisa@gmail.com', 'customer123', '081111111111', 'customer'),
('Fadia Nabila', 'fadia@gmail.com', 'customer123', '081222222222', 'customer');

-- Kategori produk
INSERT INTO category (category_name) VALUES
('Tanaman Hias'),
('Dalam Ruangan'),
('Luar Ruangan'),
('Perabot Ramah Lingkungan'),
('Produk Daur Ulang');

-- Produk contoh
INSERT INTO product (product_name, price, stock, description, eco_label, eco_impact, verification_status, category_id, admin_id) VALUES
('Mammillaria Bloom', 250000, 20, 'Kaktus hias cantik cocok untuk meja kerja, perawatan mudah.', 'Biodegradable', 'Membantu menyerap CO2 dan menyaring udara dalam ruangan.', 'verified', 1, 1),
('Strelitzia', 250000, 15, 'Tanaman hias tropis dengan daun besar nan eksotis.', 'Pestisida Nabati', 'Meningkatkan kualitas udara dan kelembapan ruangan.', 'verified', 2, 1),
('Philodendron', 250000, 18, 'Tanaman daun hijau yang mudah dirawat dan tahan banting.', 'Organik', 'Membantu mengurangi polutan udara di ruangan tertutup.', 'verified', 2, 1),
('Tas Belanja Kain Goni', 45000, 50, 'Tas ramah lingkungan pengganti plastik sekali pakai.', 'Reusable', 'Mengurangi sampah plastik hingga 80% per penggunaan rumah tangga.', 'verified', 5, 1);

-- Konten edukasi contoh
INSERT INTO education_content (admin_id, title, content) VALUES
(1, 'Mengapa Produk Ramah Lingkungan Penting?', 'Produk ramah lingkungan membantu mengurangi jejak karbon dan sampah yang dihasilkan dari aktivitas konsumsi sehari-hari. Dengan memilih produk eco-friendly, kita turut berkontribusi pada pencapaian SDGs khususnya poin ke-12 (Konsumsi dan Produksi yang Bertanggung Jawab).'),
(1, 'Tips Memilih Tanaman Hias untuk Kualitas Udara', 'Beberapa tanaman seperti Philodendron dan Lidah Mertua diketahui efektif menyaring udara dalam ruangan dan meningkatkan kadar oksigen di rumah.');
