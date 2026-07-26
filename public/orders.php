<?php

require __DIR__ . '/../app/config/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

?>

<?php $page_title = 'pesanan'; include __DIR__ . '/../app/partials/head.php'; ?>

<!-- header section starts  -->
<?php include __DIR__ . '/../app/partials/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>pesanan</h3>
   <p><a href="home.php">home</a> <span> / orders</span></p>
</div>

<section class="orders">

   <h1 class="title">pesanan anda</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">silakan login untuk melihat pesanan anda</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>payment method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>total price : <span>Rp. <?= number_format($fetch_orders['total_price'], 0, ',', '.'); ?>/-</span></p>
      <p> payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">belum ada pesanan yang dibuat!</p>';
      }
      }
   ?>

   </div>

</section>


<!-- footer section starts  -->
<?php include __DIR__ . '/../app/partials/footer.php'; ?>
<!-- footer section ends -->


<!-- custom js file link  -->
<script src="assets/js/script.js"></script>

</body>
</html>