<?php

require __DIR__ . '/../../app/config/connect.php';
require __DIR__ . '/../../app/config/auth.php';

$admin = require_admin($conn);
$admin_id = $admin['id'];

// Naikkan pembeli jadi admin. Tidak perlu membuat akun baru —
// akun yang sama tetap punya keranjang dan riwayat pesanannya.
if(isset($_GET['promote'])){
   $promote_id = $_GET['promote'];
   $promote_user = $conn->prepare("UPDATE `users` SET is_admin = 1 WHERE id = ?");
   $promote_user->execute([$promote_id]);
   header('location:users_accounts.php');
   exit;
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   if($delete_id == $admin_id){
      $message[] = 'tidak bisa menghapus akun sendiri!';
   }else{
      // Keranjang dan pesanan ikut terhapus lewat ON DELETE CASCADE
      // di skema, jadi cukup hapus barisnya di `users`.
      $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
      $delete_users->execute([$delete_id]);
      header('location:users_accounts.php');
      exit;
   }
}

?>

<?php $page_title = 'akun user'; include __DIR__ . '/../../app/partials/admin_head.php'; ?>

<?php include __DIR__ . '/../../app/partials/admin_header.php' ?>

<?php include __DIR__ . '/../../app/partials/admin_back.php' ?>

<!-- user accounts section starts  -->

<section class="accounts">

   <h1 class="heading">akun user</h1>

   <div class="box-container">

   <?php
      $select_account = $conn->prepare("SELECT * FROM `users` ORDER BY is_admin DESC, id");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> nama : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> email : <span><?= $fetch_accounts['email']; ?></span> </p>
      <p> peran : <span><?= $fetch_accounts['is_admin'] ? 'admin' : 'pembeli'; ?></span> </p>
      <div class="flex-btn">
         <?php if(!$fetch_accounts['is_admin']){ ?>
         <a href="users_accounts.php?promote=<?= $fetch_accounts['id']; ?>" class="option-btn" onclick="return confirm('jadikan akun ini admin?');">jadikan admin</a>
         <?php } ?>
         <?php if($fetch_accounts['id'] != $admin_id){ ?>
         <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('hapus akun ini beserta keranjang dan pesanannya?');">delete</a>
         <?php } ?>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">tidak ada akun yang tersedia</p>';
   }
   ?>

   </div>

</section>

<!-- user accounts section ends -->

<!-- custom js file link  -->
<script src="../assets/js/admin_script.js"></script>

</body>
</html>
