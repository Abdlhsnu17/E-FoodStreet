-- =========================================================
--  AbdiBites — skema database
--  Target: MySQL 8.0+ / MariaDB 10.4+
--
--  Cara pakai (dari root project):
--     mysql -u root -p < schema.sql
--
--  PERINGATAN: baris DROP DATABASE di bawah menghapus database
--  `food_db` beserta seluruh isinya. Komentari kalau tidak mau.
-- =========================================================

DROP DATABASE IF EXISTS `food_db`;
CREATE DATABASE `food_db`
   DEFAULT CHARACTER SET utf8mb4
   DEFAULT COLLATE utf8mb4_unicode_ci;

USE `food_db`;

SET NAMES utf8mb4;


-- ---------------------------------------------------------
--  users — SATU tabel untuk semua akun
--
--  Admin dan pembeli tidak dipisah ke tabel berbeda. Yang
--  membedakan hanya kolom `is_admin`:
--     0 = pembeli (default saat mendaftar lewat register.php)
--     1 = admin, boleh membuka /admin
--
--  Konsekuensinya satu akun admin tetap punya keranjang,
--  pesanan, dan profil seperti pembeli biasa — tidak ada
--  data yang perlu diduplikasi antar tabel.
-- ---------------------------------------------------------

CREATE TABLE `users` (
   `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
   `name`       VARCHAR(50)  NOT NULL,
   `email`      VARCHAR(100) NOT NULL,
   `number`     VARCHAR(20)  NOT NULL,
   `password`   CHAR(40)     NOT NULL COMMENT 'sha1, lihat register.php',
   `address`    VARCHAR(500) NOT NULL DEFAULT '',
   `is_admin`   TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = boleh membuka panel admin',
   `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE KEY `uq_users_email` (`email`),
   KEY `idx_users_is_admin` (`is_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
--  products — katalog menu
--  `category` diisi salah satu dari: yummy food / yummy dishes /
--  yummy drinks / yummy desserts (lihat home.php & admin/products.php)
-- ---------------------------------------------------------

CREATE TABLE `products` (
   `id`         INT UNSIGNED   NOT NULL AUTO_INCREMENT,
   `name`       VARCHAR(100)   NOT NULL,
   `category`   VARCHAR(50)    NOT NULL,
   `price`      INT UNSIGNED   NOT NULL COMMENT 'rupiah, bilangan bulat',
   `image`      VARCHAR(255)   NOT NULL COMMENT 'nama berkas di uploaded_img/',
   `created_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `idx_products_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
--  cart — keranjang per user
--  Satu user hanya boleh punya satu baris per produk; kalau
--  produk ditambah lagi, components/add_cart.php menaikkan qty.
-- ---------------------------------------------------------

CREATE TABLE `cart` (
   `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
   `user_id`  INT UNSIGNED NOT NULL,
   `pid`      INT UNSIGNED NOT NULL,
   `name`     VARCHAR(100) NOT NULL COMMENT 'disalin saat masuk keranjang',
   `price`    INT UNSIGNED NOT NULL COMMENT 'disalin saat masuk keranjang',
   `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
   `image`    VARCHAR(255) NOT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `uq_cart_user_product` (`user_id`, `pid`),
   KEY `idx_cart_pid` (`pid`),
   CONSTRAINT `fk_cart_user`    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)    ON DELETE CASCADE,
   CONSTRAINT `fk_cart_product` FOREIGN KEY (`pid`)     REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
--  orders — pesanan yang sudah di-checkout
--  `total_products` adalah ringkasan teks "nama (xQty), ..."
--  yang dirakit di checkout.php, bukan relasi.
-- ---------------------------------------------------------

CREATE TABLE `orders` (
   `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
   `user_id`        INT UNSIGNED  NOT NULL,
   `name`           VARCHAR(50)   NOT NULL,
   `number`         VARCHAR(20)   NOT NULL,
   `email`          VARCHAR(100)  NOT NULL,
   `method`         VARCHAR(50)   NOT NULL,
   `address`        VARCHAR(500)  NOT NULL,
   `total_products` VARCHAR(1000) NOT NULL,
   `total_price`    INT UNSIGNED  NOT NULL,
   `placed_on`      DATE          NOT NULL DEFAULT (CURRENT_DATE),
   `payment_status` VARCHAR(20)   NOT NULL DEFAULT 'pending',
   PRIMARY KEY (`id`),
   KEY `idx_orders_user` (`user_id`),
   KEY `idx_orders_status` (`payment_status`),
   CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
--  messages — pesan dari form kontak
--  Tanpa foreign key: contact.php mengizinkan pengunjung yang
--  belum login mengirim pesan, jadi user_id boleh NULL.
-- ---------------------------------------------------------

CREATE TABLE `messages` (
   `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
   `user_id`    INT UNSIGNED     NULL DEFAULT NULL COMMENT 'NULL = tamu belum login',
   `name`       VARCHAR(50)  NOT NULL,
   `email`      VARCHAR(100) NOT NULL,
   `number`     VARCHAR(20)  NOT NULL,
   `message`    VARCHAR(500) NOT NULL,
   `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `idx_messages_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
--  DATA AWAL
-- =========================================================

-- Akun admin bawaan — login lewat login.php seperti pembeli biasa:
--    email    : admin@abdibites.test
--    password : admin123
-- Ganti lewat admin/update_profile.php setelah login pertama.
INSERT INTO `users` (`name`, `email`, `number`, `password`, `is_admin`) VALUES
('Administrator', 'admin@abdibites.test', '081200000000',
 'f865b53623b121fd34ee5426c792e5c33af8c227', 1);

-- Katalog menu
INSERT INTO `products` (`name`, `category`, `price`, `image`) VALUES
('Sweet Orange Juice',      'yummy drinks',   15000, 'sweet orange juice.png'),
('Infused Water',           'yummy drinks',   24000, 'infused water.png'),
('Velvet Coffee',           'yummy drinks',   20000, 'Velvet Coffee.png'),
('Green Lemon Cocktail',    'yummy drinks',   28000, 'green lemon cocktail.png'),
('Ultimate Cheese Burger',  'yummy food',     30000, 'burger-1.png'),
('Meat Lover Pizza',        'yummy food',     45000, 'pizza-3.png'),
('Supreme Pepperoni Pizza', 'yummy food',     40000, 'Pizza Pepperoni.png'),
('Salmon Crispy Burger',    'yummy food',     37000, 'salmon crispy burger.png'),
('Seafood Delight Noodle',  'yummy dishes',   28000, 'Seafood_Delight_Noodles2-removebg-preview.png'),
('Spaghetti Supreme',       'yummy dishes',   27000, 'Spaghetti Supreme.png'),
('Shoyu Ramen',             'yummy dishes',   28000, 'Ramen Tempura Sushi Miso soup.png'),
('Strawberry Cheesecake',   'yummy desserts', 23000, 'Strawberry-Cheesecake-Parfait.png'),
('Molten Chocolate Cake',   'yummy desserts', 27000, 'Molten chocolate cake.png');

-- Selain akun admin di atas, `users` sengaja dibiarkan kosong —
-- pembeli mendaftar sendiri lewat register.php (is_admin = 0).
