<?php

// Sesi & pengecekan peran.
//
// Semua akun ada di tabel `users`; kolom `is_admin` yang menentukan
// siapa boleh membuka /admin. Karena itu hanya ada satu sesi
// ($_SESSION['user_id']) untuk admin maupun pembeli.
//
// Butuh $conn dari app/config/connect.php — require file itu lebih dulu.

if(session_status() === PHP_SESSION_NONE){
   session_start();
}

// Baris user yang sedang login, atau null kalau belum login.
// Data dibaca ulang dari database tiap request supaya pencabutan
// hak admin langsung berlaku, bukan menunggu user logout.
function logged_in_user($conn){
   if(!isset($_SESSION['user_id'])){
      return null;
   }

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $select_user->execute([$_SESSION['user_id']]);
   $user = $select_user->fetch(PDO::FETCH_ASSOC);

   // Akun sudah dihapus tapi cookie sesi masih ada.
   if(!$user){
      session_unset();
      session_destroy();
      return null;
   }

   return $user;
}

// Wajib login. Kalau belum, alihkan ke halaman login dan hentikan skrip.
function require_login($conn, $login_url = 'login.php'){
   $user = logged_in_user($conn);

   if(!$user){
      header('location:'.$login_url);
      exit;
   }

   return $user;
}

// Wajib login DAN is_admin = 1. Dipakai di seluruh halaman /admin.
function require_admin($conn, $login_url = '../login.php', $home_url = '../home.php'){
   $user = require_login($conn, $login_url);

   if(!$user['is_admin']){
      header('location:'.$home_url);
      exit;
   }

   return $user;
}

?>
