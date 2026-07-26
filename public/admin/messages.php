<?php

require __DIR__ . '/../../app/config/connect.php';
require __DIR__ . '/../../app/config/auth.php';

$admin = require_admin($conn);
$admin_id = $admin['id'];

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<?php $page_title = 'pesan'; include __DIR__ . '/../../app/partials/admin_head.php'; ?>

<?php include __DIR__ . '/../../app/partials/admin_header.php' ?>

<?php include __DIR__ . '/../../app/partials/admin_back.php' ?>

<!-- messages section starts  -->

<section class="messages">

   <h1 class="heading">pesan</h1>

   <div class="box-container">

   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> nama : <span><?= $fetch_messages['name']; ?></span> </p>
      <p> nomor handphone : <span><?= $fetch_messages['number']; ?></span> </p>
      <p> email : <span><?= $fetch_messages['email']; ?></span> </p>
      <p> pesan : <span><?= $fetch_messages['message']; ?></span> </p>
      <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('delete this message?');">delete</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">kamu tidak punya pesan</p>';
      }
   ?>

   </div>

</section>

<!-- messages section ends -->

<!-- custom js file link  -->
<script src="../assets/js/admin_script.js"></script>

</body>
</html>