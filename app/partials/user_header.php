<?php
/** @var PDO $conn koneksi database, diisi app/config/connect.php */
// $user_id diisi tiap halaman publik dari session; '' berarti belum login.
$user_id = $user_id ?? ($_SESSION['user_id'] ?? '');

if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">Abdi<span>Bites</span></a>

      <nav class="navbar">
         <a href="home.php">home</a>
         <a href="about.php">about</a>
         <a href="menu.php">menu</a>
         <a href="orders.php">pesanan</a>
         <a href="contact.php">kontak</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <?php if($fetch_profile['is_admin']){ ?>
         <p class="account">
            <a href="admin/dashboard.php">buka panel admin</a>
         </p>
         <?php } ?>
         <?php
            }else{
         ?>
            <p class="name">silahkan login terlebih dahulu!</p>
            <a href="login.php" class="btn">login</a>
            <p class="account">
               belum punya akun? <a href="register.php">daftar</a>
            </p>
         <?php
          }
         ?>
      </div>

   </section>

</header>

