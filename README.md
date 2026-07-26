# AbdiBites

Website pemesanan makanan: katalog menu, keranjang, checkout, dan panel admin.
PHP + MySQL murni — tanpa framework, tanpa Composer, tanpa build step.

Dokumen produk lengkap: [`docs/PRD.md`](docs/PRD.md).

## Kebutuhan

| | |
|---|---|
| PHP | 8.0+ (diuji pada 8.5), ekstensi `pdo_mysql` aktif |
| MySQL | 8.0+ / MariaDB 10.4+ — paling mudah lewat XAMPP |
| Browser | apa saja; tidak ada dependensi Node/npm |

## Menjalankan

1. Nyalakan **MySQL** lewat XAMPP Control Panel (Apache **tidak** perlu).
   Lewat terminal:

   ```bash
   sudo /Applications/XAMPP/xamppfiles/xampp startmysql
   ```

2. Dari folder proyek:

   ```bash
   ./start.sh
   ```

   Script ini mengecek koneksi MySQL, mengimpor `database/schema.sql` kalau
   database `food_db` belum ada, lalu menyalakan server PHP dengan `public/`
   sebagai docroot.

3. Buka <http://localhost:8000>

Berhenti dengan `Ctrl+C`.

### Tanpa script

```bash
php -S localhost:8000 -t public
```

Opsi `-t public` **wajib**. Tanpa itu server memakai akar proyek sebagai
docroot, di sana tidak ada `index.php`, dan setiap permintaan `/` menjawab
`404 Not Found`.

Impor database manual (sekali saja):

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -h 127.0.0.1 < database/schema.sql
```

> `schema.sql` diawali `DROP DATABASE IF EXISTS food_db` — menjalankannya ulang
> menghapus semua data yang sudah ada. Itu memang gunanya untuk reset.

### Deploy ke Apache/hosting

Arahkan `DocumentRoot` (atau isi `htdocs`) ke folder `public/`, bukan ke akar
proyek. Folder `app/` harus tetap di luar docroot supaya kredensial database
tidak bisa diminta lewat URL.

## Akses

| | |
|---|---|
| Toko | <http://localhost:8000> |
| Login (semua akun) | <http://localhost:8000/login.php> |
| Panel admin | <http://localhost:8000/admin/dashboard.php> |

Admin dan pembeli memakai **form login yang sama**. Setelah masuk, pemilik akun
ber-`is_admin` diarahkan ke dashboard, pembeli ke beranda.

Akun admin bawaan dari `schema.sql`:
**`admin@abdibites.test` / `admin123`**. Ganti lewat *update profile* di panel
admin setelah login pertama.

Akun pembeli tidak ada bawaannya — daftar sendiri lewat halaman *register*.

**Menambah admin** — dua cara, keduanya dari dalam panel:

- *users* → tombol **jadikan admin** pada akun pembeli yang sudah ada.
  Keranjang dan riwayat pesanannya tetap utuh.
- *admins* → **tambah admin** untuk membuat akun baru sekaligus.

Mencabut hak admin (*admins* → **cabut admin**) tidak menghapus akun, hanya
menurunkannya jadi pembeli. Admin tidak bisa mencabut atau menghapus dirinya
sendiri — itu yang menjamin selalu tersisa minimal satu admin.

## Fitur

**Pengunjung**

- Beranda dengan slider produk unggulan per kategori
- Katalog menu, filter kategori (`yummy food`, `yummy dishes`, `yummy drinks`,
  `yummy desserts`), pencarian nama produk
- Quick view produk
- Keranjang: tambah, ubah jumlah, hapus item, kosongkan keranjang
- Checkout dengan pilihan pembayaran: *cash on delivery*, *credit card*,
  *gopay*, *ovo*
- Riwayat pesanan beserta status pembayaran
- Profil: ubah nama/email/nomor/password dan alamat pengiriman
- Form kontak (bisa dikirim tanpa login)

**Admin**

- Dashboard ringkasan: total pending, total selesai, jumlah pesanan, produk,
  pembeli, admin, dan pesan masuk
- Produk: tambah, ubah, hapus, unggah foto ke `public/assets/uploads/`
- Pesanan masuk: ubah status pembayaran (*pending* / *completed*), hapus
- Kelola akun: promosikan pembeli jadi admin, cabut hak admin, hapus akun
- Kotak masuk pesan dari form kontak
- Tambah akun admin baru

## Database

Koneksi diatur di [`app/config/connect.php`](app/config/connect.php) —
`127.0.0.1:3306`, database `food_db`, user `root` tanpa password.

Dipakai `127.0.0.1` dan bukan `localhost` dengan sengaja: di macOS, driver MySQL
mengartikan `localhost` sebagai unix socket, dan path socket default PHP tidak
cocok dengan milik XAMPP. `127.0.0.1` memaksa koneksi TCP.

Kalau di-deploy ke XAMPP/hosting biasa (Apache + htdocs), `localhost` justru
yang benar — ganti di file itu.

Lima tabel: `users`, `products`, `cart`, `orders`, `messages`.

**Semua akun ada di `users`** — tidak ada tabel `admin` terpisah. Yang
membedakan hanya kolom `is_admin` (`0` = pembeli, `1` = boleh membuka `/admin`).
Konsekuensinya akun admin tetap punya keranjang, pesanan, dan profil seperti
pembeli biasa, dan tidak ada data yang perlu diduplikasi antar tabel.

`cart` dan `orders` memakai foreign key ke `users` dengan `ON DELETE CASCADE`;
`messages` sengaja tanpa foreign key karena tamu yang belum login boleh
mengirim pesan (`user_id` NULL). Harga disimpan sebagai bilangan bulat rupiah.

## Struktur

Hanya isi `public/` yang bisa dijangkau browser. Kode bersama dan kredensial
database ada di `app/`, di luar docroot, jadi tidak bisa diminta lewat URL.

```
public/                 satu-satunya folder yang dilayani web server
  index.php             pengalihan ke home.php
  *.php                 halaman pengunjung
  logout.php            akhiri sesi pengunjung
  admin/                panel admin (+ logout.php-nya sendiri)
  assets/css/           style.css (pengunjung), admin_style.css (admin)
  assets/js/            script.js, admin_script.js
  assets/images/        aset statis + logo
  assets/uploads/       foto produk yang diunggah lewat panel admin

app/                    tidak bisa diakses browser
  config/connect.php    koneksi PDO
  config/auth.php       sesi + require_login() / require_admin()
  partials/             head, header, footer — dipakai bersama
  actions/add_cart.php  logika tambah-ke-keranjang

database/schema.sql     skema + data awal (dipakai start.sh)
docs/PRD.md             dokumen kebutuhan produk
docs/assets-cadangan/   gambar cadangan, tidak dirujuk kode
```

Blok `<head>` tiap halaman berasal dari satu file. Untuk menambah halaman baru
di `public/`:

```php
<?php $page_title = 'judul halaman'; include __DIR__ . '/../app/partials/head.php'; ?>
```

Tambahkan `$use_swiper = true;` sebelum include kalau halaman memakai slider.
Halaman admin ada satu tingkat lebih dalam, jadi memakai
`__DIR__ . '/../../app/partials/admin_head.php'`.

Semua include memakai `__DIR__` supaya tidak bergantung pada working directory.
URL aset tetap relatif: `assets/...` dari halaman pengunjung, `../assets/...`
dari halaman admin.

## Desain

Tema "Hijau Segar". Seluruh token warna, radius, dan bayangan didefinisikan
sekali di blok `:root` pada masing-masing file CSS — jangan mendefinisikan
ulang di tempat lain.

## Masalah umum

| Gejala | Sebab & solusi |
|---|---|
| `404 Not Found` di `/` | Server dijalankan tanpa `-t public`. Pakai `./start.sh` atau `php -S localhost:8000 -t public`. |
| `SQLSTATE[HY000] [2002]` | MySQL mati. Nyalakan lewat XAMPP. |
| `Unknown database 'food_db'` | Database belum diimpor. Jalankan `./start.sh` atau impor `database/schema.sql` manual. |
| Foto produk tidak muncul | Berkas tidak ada di `public/assets/uploads/`, atau folder itu tidak bisa ditulis PHP. |
| Port 8000 dipakai proses lain | Ubah `PORT` di `start.sh`, atau `php -S localhost:8080 -t public`. |

## Batasan yang diketahui

Proyek ini tugas kuliah, bukan aplikasi produksi. Yang perlu diperbaiki
sebelum dipakai sungguhan tercatat di [`docs/PRD.md`](docs/PRD.md) — yang utama:
password di-hash dengan SHA-1 tanpa salt (seharusnya `password_hash()`),
`FILTER_SANITIZE_STRING` sudah deprecated sejak PHP 8.1, tidak ada proteksi
CSRF, dan tidak ada validasi tipe/ukuran berkas saat unggah foto produk.
