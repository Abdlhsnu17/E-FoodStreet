<?php

require __DIR__ . '/../../app/config/connect.php';
require __DIR__ . '/../../app/config/auth.php';

$admin = require_admin($conn);
$admin_id = $admin['id'];

if(isset($_POST['submit'])){

   $name = trim($_POST['name']);
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   $email = trim($_POST['email']);
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $number = trim($_POST['number']);
   $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
   $pass = sha1($_POST['pass']);
   $cpass = sha1($_POST['cpass']);

   // Admin baru = baris biasa di `users` dengan is_admin = 1,
   // jadi email tetap harus unik seperti akun pembeli.
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);

   if($select_user->rowCount() > 0){
      $message[] = 'email sudah dipakai! kalau ini akun pembeli yang mau dijadikan admin, naikkan lewat halaman users.';
   }else{
      if($pass != $cpass){
         $message[] = 'konfirmasi password tidak cocok!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `users`(name, email, number, password, address, is_admin) VALUES(?,?,?,?,?,1)");
         $insert_admin->execute([$name, $email, $number, $cpass, '']);
         $message[] = 'admin baru berhasil dibuat!';
      }
   }

}

?>

<?php $page_title = 'register'; include __DIR__ . '/../../app/partials/admin_head.php'; ?>

<?php include __DIR__ . '/../../app/partials/admin_header.php' ?>

<?php include __DIR__ . '/../../app/partials/admin_back.php' ?>

<!-- register admin section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>tambah admin</h3>
      <input type="text" name="name" maxlength="50" required placeholder="nama lengkap" class="box">
      <input type="email" name="email" maxlength="100" required placeholder="email" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="number" maxlength="20" required placeholder="nomor telepon" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="50" required placeholder="password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" maxlength="50" required placeholder="konfirmasi password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="buat admin" name="submit" class="btn">
   </form>

</section>

<!-- register admin section ends -->
















<!-- custom js file link  -->
<script src="../assets/js/admin_script.js"></script>

</body>
</html>