<?php

require __DIR__ . '/../../app/config/connect.php';
require __DIR__ . '/../../app/config/auth.php';

$admin = require_admin($conn);
$admin_id = $admin['id'];

if(isset($_POST['submit'])){

   $name = trim($_POST['name']);
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

   // Nama tidak perlu unik di `users` — yang unik email.
   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $admin_id]);
      $admin['name'] = $name;
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'password lama tidak cocok!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'konfirmasi password tidak cocok!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'password berhasil diperbarui!';
         }else{
            $message[] = 'silakan masukkan password baru!';
         }
      }
   }

}

?>

<?php $page_title = 'profile update'; include __DIR__ . '/../../app/partials/admin_head.php'; ?>

<?php include __DIR__ . '/../../app/partials/admin_header.php' ?>

<?php include __DIR__ . '/../../app/partials/admin_back.php' ?>

<!-- admin profile update section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>update profile</h3>
      <input type="text" name="name" maxlength="50" class="box" placeholder="<?= $admin['name']; ?>">
      <input type="password" name="old_pass" maxlength="20" placeholder="enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>

<!-- admin profile update section ends -->

<!-- custom js file link  -->
<script src="../assets/js/admin_script.js"></script>

</body>
</html>