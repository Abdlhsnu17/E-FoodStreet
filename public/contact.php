<?php

require __DIR__ . '/../app/config/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = null;   // tamu belum login — messages.user_id memang boleh NULL
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'sudah mengirim pesan!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'pesan berhasil terkirim!';

   }

}

?>

<?php $page_title = 'kontak'; include __DIR__ . '/../app/partials/head.php'; ?>

<!-- header section starts  -->
<?php include __DIR__ . '/../app/partials/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>kontak kami</h3>
   <p><a href="home.php">home</a> <span> / kontak</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="assets/images/contact-img.svg" alt="">
      </div>

      <form action="" method="post">
         <h3>Berikan pesan untuk kami!</h3>
         <input type="text" name="name" maxlength="50" class="box" placeholder="masukkan nama anda" required>
         <input type="number" name="number" min="0" max="9999999999" class="box" placeholder="masukkan nomor handphone anda" required maxlength="10">
         <input type="email" name="email" maxlength="50" class="box" placeholder="masukkan email anda" required>
         <textarea name="msg" class="box" required placeholder="masukkan pesan anda" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="kirim pesan" name="send" class="btn">
      </form>

   </div>

</section>

<!-- contact section ends -->

<!-- footer section starts  -->
<?php include __DIR__ . '/../app/partials/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="assets/js/script.js"></script>

</body>
</html>