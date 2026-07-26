<?php

require __DIR__ . '/../app/config/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = trim($_POST['email']);
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $pass = sha1($_POST['pass']);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      // Satu form login untuk semua; tujuannya yang berbeda menurut peran.
      header('location:'.($row['is_admin'] ? 'admin/dashboard.php' : 'home.php'));
      exit;
   }else{
      $message[] = 'username atau password salah!';
   }

}

?>

<?php $page_title = 'login'; include __DIR__ . '/../app/partials/head.php'; ?>

<!-- header section starts  -->
<?php include __DIR__ . '/../app/partials/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" name="submit" class="btn">
      <p>don't have an account? <a href="register.php">register now</a></p>
   </form>

</section>


<?php include __DIR__ . '/../app/partials/footer.php'; ?>

<!-- custom js file link  -->
<script src="assets/js/script.js"></script>

</body>
</html>