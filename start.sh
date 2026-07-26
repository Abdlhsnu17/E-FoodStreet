#!/bin/bash
# Jalankan food website di http://localhost:8000
#
# Syarat: MySQL XAMPP hidup (nyalakan lewat XAMPP Control Panel, atau:
#   sudo /Applications/XAMPP/xamppfiles/xampp startmysql)

cd "$(dirname "$0")"

MYSQL=/Applications/XAMPP/xamppfiles/bin/mysql
PORT=8000
DOCROOT=public

if ! $MYSQL -u root -h 127.0.0.1 -e "use food_db" 2>/dev/null; then
   echo "Database food_db belum ada / MySQL mati. Mengimpor database/schema.sql..."
   $MYSQL -u root -h 127.0.0.1 -e "SELECT 1;" >/dev/null || {
      echo "Gagal konek ke MySQL. Nyalakan dulu MySQL di XAMPP." >&2
      exit 1
   }
   # schema.sql membuat database `food_db` sendiri, jadi tidak perlu CREATE DATABASE.
   $MYSQL -u root -h 127.0.0.1 < database/schema.sql
   echo "Import selesai."
fi

echo "Server jalan di http://localhost:$PORT  (Ctrl+C untuk berhenti)"
php -S localhost:$PORT -t "$DOCROOT"
