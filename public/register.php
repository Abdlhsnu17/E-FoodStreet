<?php

require __DIR__ . '/../app/config/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = trim($_POST['name']);
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   $email = trim($_POST['email']);
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $number = trim($_POST['number']);
   $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
   $pass = sha1($_POST['pass']);
   $cpass = sha1($_POST['cpass']);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email atau nomor sudah ada!';
   }else{
      if($pass != $cpass){
         $message[] = 'konfirmasi password tidak cocok!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password, address) VALUES(?,?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass, '']);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
         }
      }
   }

}

?>

<?php $page_title = 'register'; include __DIR__ . '/../app/partials/head.php'; ?>

<!-- header section starts  -->
<?php include __DIR__ . '/../app/partials/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="name" required placeholder="enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="enter your number" class="box" min="0" max="999999999999" maxlength="12">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>

<?php include __DIR__ . '/../app/partials/footer.php'; ?>

<!-- custom js file link  -->
<script src="assets/js/script.js"></script>

</body>
</html>