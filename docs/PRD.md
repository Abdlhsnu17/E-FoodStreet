# PRD — AbdiBites

**Produk:** AbdiBites, website pemesanan makanan
**Versi dokumen:** 1.0 — 26 Juli 2026
**Status:** menggambarkan aplikasi yang sudah berjalan (as-built), plus rencana lanjutan
**Stack:** PHP 8 (tanpa framework) + MySQL 8 + HTML/CSS/JS statis

---

## 1. Latar belakang

Rumah makan kecil menerima pesanan lewat chat: menu dikirim sebagai foto, harga
ditanyakan ulang, pesanan dicatat manual, dan rekap harian dihitung dengan
tangan. Akibatnya pesanan mudah tertukar, harga tidak konsisten, dan pemilik
tidak punya angka penjualan yang bisa dipercaya.

AbdiBites memindahkan katalog dan proses pemesanan ke satu website, dengan
panel admin untuk mengelola menu dan pesanan.

## 2. Tujuan

| Tujuan | Ukuran keberhasilan |
|---|---|
| Pengunjung bisa memesan tanpa bertanya harga | Checkout selesai tanpa interaksi manual apa pun |
| Katalog selalu benar | Perubahan harga/menu tampil langsung setelah admin menyimpan |
| Pemilik tahu angka penjualan | Dashboard menampilkan total pending dan total selesai secara real time |
| Bisa dijalankan di laptop mana pun | Satu perintah (`./start.sh`) tanpa instalasi dependensi |

### Bukan tujuan (out of scope)

- Pembayaran online sungguhan (payment gateway) — metode bayar hanya dicatat
- Pelacakan kurir / status pengiriman
- Multi-cabang, multi-mata uang, atau multi-bahasa
- Aplikasi mobile native
- Notifikasi email/WhatsApp otomatis

## 3. Pengguna

| Peran | Kebutuhan utama |
|---|---|
| **Tamu** (belum login) | Melihat menu, mencari produk, mengirim pesan lewat form kontak |
| **Pelanggan** (user terdaftar) | Keranjang, checkout, riwayat pesanan, kelola profil & alamat |
| **Admin** | Semua yang bisa dilakukan pelanggan, **plus** kelola produk, proses pesanan, kelola akun & pesan masuk |

Peran bukan tabel terpisah. Semua akun ada di `users`, dan kolom `is_admin`
menentukan siapa boleh membuka `/admin`. Karena itu admin adalah pelanggan yang
diberi akses tambahan — bukan jenis akun yang berbeda — dan seorang pelanggan
bisa dinaikkan jadi admin tanpa kehilangan keranjang atau riwayat pesanannya.

Keranjang dan checkout mensyaratkan login. Tamu yang menekan "tambah ke
keranjang" diarahkan ke halaman login.

## 4. Alur utama

**Memesan**

1. Tamu membuka beranda → melihat produk unggulan per kategori
2. Menelusuri menu / filter kategori / mencari nama produk
3. Quick view untuk detail → tambah ke keranjang (butuh login)
4. Keranjang: ubah jumlah, hapus item, lihat grand total
5. Checkout: isi nama, nomor, email, alamat, pilih metode pembayaran
6. Pesanan tersimpan dengan status `pending`, keranjang dikosongkan
7. Riwayat pesanan menampilkan detail dan status pembayaran

**Memproses pesanan (admin)**

1. Login di `/login.php` dengan akun ber-`is_admin` → diarahkan ke dashboard
2. Dashboard menampilkan total pending & selesai
3. Buka *placed orders* → ubah status jadi `completed` setelah dibayar
4. Kelola produk saat menu atau harga berubah

## 5. Kebutuhan fungsional

### 5.1 Akun pengunjung

| ID | Kebutuhan |
|---|---|
| U-1 | Registrasi dengan nama, email, nomor, password + konfirmasi password; akun baru selalu `is_admin = 0` |
| U-2 | Email harus unik (dikunci `UNIQUE KEY` di tabel `users`) |
| U-3 | Satu form login untuk semua peran; sesi disimpan di `$_SESSION['user_id']` |
| U-4 | Setelah login, `is_admin = 1` diarahkan ke dashboard admin, `0` ke beranda |
| U-5 | Logout menghancurkan sesi dan kembali ke halaman login |
| U-6 | Ubah profil: nama, email, nomor, dan password (butuh password lama) |
| U-7 | Ubah alamat pengiriman terpisah dari profil |

### 5.2 Katalog

| ID | Kebutuhan |
|---|---|
| C-1 | Beranda menampilkan slider produk per kategori |
| C-2 | Halaman menu menampilkan seluruh produk |
| C-3 | Filter berdasarkan kategori: `yummy food`, `yummy dishes`, `yummy drinks`, `yummy desserts` |
| C-4 | Pencarian berdasarkan nama produk |
| C-5 | Quick view menampilkan satu produk beserta tombol tambah ke keranjang |
| C-6 | Harga tampil dalam format rupiah (`Rp. 30.000`) |

### 5.3 Keranjang & checkout

| ID | Kebutuhan |
|---|---|
| K-1 | Satu user hanya punya satu baris per produk; menambah produk yang sama menaikkan `quantity` (dijaga `UNIQUE KEY (user_id, pid)`) |
| K-2 | Jumlah per item 1–99 |
| K-3 | Ubah jumlah, hapus satu item, dan kosongkan seluruh keranjang |
| K-4 | Grand total dihitung dari `price × quantity` seluruh item |
| K-5 | Checkout wajib mengisi alamat; keranjang kosong tidak bisa checkout |
| K-6 | Metode pembayaran: *cash on delivery*, *credit card*, *gopay*, *ovo* |
| K-7 | Isi pesanan disimpan sebagai ringkasan teks `nama (xQty), ...` di kolom `total_products` |
| K-8 | Setelah pesanan tersimpan, keranjang user dikosongkan |

### 5.4 Panel admin

| ID | Kebutuhan |
|---|---|
| A-1 | Semua halaman `/admin` memanggil `require_admin()` — menolak yang belum login (ke `login.php`) dan yang bukan admin (ke `home.php`) |
| A-2 | Status admin dibaca ulang dari database tiap request, jadi pencabutan hak langsung berlaku tanpa menunggu user logout |
| A-3 | Dashboard: total pending, total selesai, jumlah pesanan, produk, pembeli, admin, pesan |
| A-4 | Produk: tambah, ubah, hapus, unggah foto ke `public/assets/uploads/` |
| A-5 | Menghapus/mengganti foto produk ikut menghapus berkas lama dari disk |
| A-6 | Pesanan: ubah status pembayaran (`pending` / `completed`), hapus pesanan |
| A-7 | Naikkan pembeli jadi admin (`is_admin = 1`) tanpa membuat akun baru |
| A-8 | Cabut hak admin (`is_admin = 0`) — akun tetap ada sebagai pembeli |
| A-9 | Admin tidak bisa mencabut hak atau menghapus akunnya sendiri; ini yang menjamin selalu tersisa minimal satu admin |
| A-10 | Buat akun admin baru langsung dari panel (nama, email, nomor, password) |
| A-11 | Menghapus akun ikut menghapus keranjang dan pesanannya lewat `ON DELETE CASCADE` |
| A-12 | Lihat dan hapus pesan dari form kontak |

### 5.5 Kontak

| ID | Kebutuhan |
|---|---|
| M-1 | Form kontak bisa dikirim tanpa login (`user_id` boleh NULL) |
| M-2 | Pesan duplikat yang persis sama ditolak |

## 6. Kebutuhan non-fungsional

| Aspek | Ketentuan |
|---|---|
| **Deployment** | Satu perintah `./start.sh`; tidak ada Composer/npm/build step |
| **Docroot** | Hanya `public/` yang dilayani web server; `app/` (berisi kredensial DB) di luar docroot |
| **Portabilitas** | Semua `include` memakai `__DIR__`, tidak bergantung working directory |
| **Database** | MySQL 8.0+ / MariaDB 10.4+, InnoDB, `utf8mb4_unicode_ci` |
| **Query** | Seluruh query memakai PDO prepared statement |
| **Harga** | Disimpan sebagai `INT UNSIGNED` rupiah — tanpa desimal, tanpa galat pembulatan float |
| **Integritas data** | `cart` & `orders` foreign key ke `users` dengan `ON DELETE CASCADE` |
| **Responsif** | Layout mengikuti layar desktop dan ponsel |
| **Konsistensi visual** | Token warna/radius/bayangan hanya di blok `:root` masing-masing CSS |
| **Reset data** | `database/schema.sql` mengembalikan database ke kondisi awal |

## 7. Model data

Lima tabel, semua InnoDB `utf8mb4`:

| Tabel | Isi | Catatan |
|---|---|---|
| `users` | **semua akun**, admin maupun pembeli | `email` unik; `is_admin` TINYINT(1) default 0, diindeks; seed `admin@abdibites.test` / `admin123` |
| `products` | katalog menu | `category` string bebas, diindeks; `image` = nama berkas di `assets/uploads/` |
| `cart` | keranjang per user | unik per `(user_id, pid)`; nama & harga **disalin** saat masuk keranjang |
| `orders` | pesanan ter-checkout | `total_products` ringkasan teks, bukan relasi; `payment_status` default `pending` |
| `messages` | pesan form kontak | tanpa foreign key; `user_id` NULL = tamu |

Nama dan harga sengaja disalin ke `cart` dan `orders`: kalau admin mengubah
harga produk, pesanan lama tetap mencatat harga saat transaksi.

`is_admin` dipilih berupa flag boolean, bukan tabel peran atau ENUM. Cukup
untuk dua peran yang ada sekarang; kalau nanti muncul peran ketiga (mis. kurir),
kolom ini perlu diganti ENUM atau tabel `roles` tersendiri.

## 8. Batasan yang diketahui

Aplikasi ini proyek belajar. Yang belum layak produksi:

| # | Masalah | Dampak | Perbaikan |
|---|---|---|---|
| B-1 | Password di-hash SHA-1 tanpa salt (`users` & `admin`) | Rentan rainbow table | Ganti ke `password_hash()` / `password_verify()` |
| B-2 | `FILTER_SANITIZE_STRING` dipakai luas | Deprecated sejak PHP 8.1, dihapus di PHP 9 | Ganti dengan validasi input + `htmlspecialchars()` saat output |
| B-3 | Tidak ada token CSRF; penghapusan di panel admin lewat tautan `GET ?delete=id` | Aksi destruktif bisa dipicu dari situs lain atau oleh prefetch | Tambah token per sesi, ubah aksi hapus jadi POST |
| B-4 | Unggah foto tidak memvalidasi tipe/ukuran berkas | Berkas sembarang bisa masuk `assets/uploads/` | Cek MIME + batas ukuran + rename berkas |
| B-5 | Harga & nama produk dikirim lewat hidden input dari halaman | Bisa dimanipulasi klien | Ambil harga dari database berdasarkan `pid` saja |
| B-6 | Kredensial database ditulis langsung di `connect.php` | Sulit dibedakan antar lingkungan | Pindah ke variabel lingkungan / file konfigurasi yang di-gitignore |
| B-7 | Tidak ada penanganan error PDO eksplisit | Kegagalan query tampil sebagai halaman kosong/warning | Set `PDO::ATTR_ERRMODE` + halaman error |
| B-8 | Tidak ada pengujian otomatis | Regresi baru ketahuan saat dipakai | Minimal smoke test alur pesan |

## 9. Rencana lanjutan

**Prioritas 1 — keamanan.** B-1 sampai B-5. Wajib sebelum dipakai orang lain.

**Prioritas 2 — kelengkapan operasional.**
- Status pesanan lebih dari dua nilai (diproses, dikirim, selesai, batal)
- Kategori sebagai tabel tersendiri, bukan string bebas
- Peran ketiga (kurir) — butuh mengganti `is_admin` jadi ENUM atau tabel peran
- Stok produk dan penanda "habis"
- Pencarian dengan filter harga dan pengurutan

**Prioritas 3 — nilai tambah.**
- Payment gateway sungguhan (Midtrans/Xendit)
- Notifikasi email saat pesanan masuk dan saat status berubah
- Ekspor rekap penjualan (CSV)
- Ulasan dan rating produk

## 10. Referensi

- Cara menjalankan, akses admin, dan troubleshooting: [`../README.md`](../README.md)
- Skema + data awal: [`../database/schema.sql`](../database/schema.sql)
- Script menjalankan: [`../start.sh`](../start.sh)
