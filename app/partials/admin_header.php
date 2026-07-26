<?php
/** @var array $admin data admin yang login, diisi require_admin() di tiap halaman admin */
$admin = $admin ?? [];

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

      <a href="dashboard.php" class="logo">AbdiBites <span>Admin</span></a>

      <nav class="navbar">
         <a href="dashboard.php">home</a>
         <a href="products.php">products</a>
         <a href="placed_orders.php">orders</a>
         <a href="admin_accounts.php">admins</a>
         <a href="users_accounts.php">users</a>
         <a href="messages.php">messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <p><?= $admin['name'] ?? ''; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
         <div class="flex-btn">
            <a href="../home.php" class="option-btn">lihat toko</a>
            <a href="register_admin.php" class="option-btn">tambah admin</a>
         </div>
         <a href="logout.php" onclick="return confirm('logout dari website ini?');" class="delete-btn">logout</a>
      </div>

   </section>

</header>