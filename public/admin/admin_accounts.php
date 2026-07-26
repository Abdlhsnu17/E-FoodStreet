<?php

require __DIR__ . '/../../app/config/connect.php';
require __DIR__ . '/../../app/config/auth.php';

$admin = require_admin($conn);
$admin_id = $admin['id'];

// Cabut hak admin: akunnya tetap ada, turun jadi pembeli biasa.
//
// Larangan mencabut diri sendiri sekaligus menjamin selalu ada minimal
// satu admin — untuk mencabut orang lain, admin yang sedang login harus
// ada, jadi jumlahnya tidak mungkin turun ke nol.
if(isset($_GET['demote'])){
   $demote_id = $_GET['demote'];

   if($demote_id == $admin_id){
      $message[] = 'tidak bisa mencabut hak admin diri sendiri!';
   }else{
      $demote_admin = $conn->prepare("UPDATE `users` SET is_admin = 0 WHERE id = ?");
      $demote_admin->execute([$demote_id]);
      header('location:admin_accounts.php');
      exit;
   }
}

?>

<?php $page_title = 'akun admin'; include __DIR__ . '/../../app/partials/admin_head.php'; ?>

<?php include __DIR__ . '/../../app/partials/admin_header.php' ?>

<?php include __DIR__ . '/../../app/partials/admin_back.php' ?>

<!-- admins accounts section starts  -->

<section class="accounts">

   <h1 class="heading">akun admin</h1>

   <div class="box-container">

   <div class="box">
      <p>tambah admin baru</p>
      <a href="register_admin.php" class="option-btn">tambah admin</a>
   </div>

   <?php
      $select_account = $conn->prepare("SELECT * FROM `users` WHERE is_admin = 1 ORDER BY id");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> nama : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> email : <span><?= $fetch_accounts['email']; ?></span> </p>
      <div class="flex-btn">
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">akun saya</a>';
            }else{
         ?>
         <a href="admin_accounts.php?demote=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('cabut hak admin akun ini? akunnya tetap ada sebagai pembeli.');">cabut admin</a>
         <?php
            }
         ?>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">tidak ada akun admin</p>';
   }
   ?>

   </div>

</section>

<!-- admins accounts section ends -->

<!-- custom js file link  -->
<script src="../assets/js/admin_script.js"></script>

</body>
</html>
