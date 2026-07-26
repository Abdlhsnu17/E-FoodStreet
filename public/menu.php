<?php

require __DIR__ . '/../app/config/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

require __DIR__ . '/../app/actions/add_cart.php';

?>

<?php $page_title = 'menu'; include __DIR__ . '/../app/partials/head.php'; ?>

<!-- header section starts  -->
<?php include __DIR__ . '/../app/partials/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>menu kami</h3>
   <p><a href="home.php">home</a> <span> / menu</span></p>
</div>

<!-- menu section starts  -->

<section class="products">

   <h1 class="title">hidangan terbaru</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <img src="assets/uploads/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Rp. </span><?= number_format($fetch_products['price'], 0, ',', '.'); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
         <button type="submit" name="add_to_cart" class="cart-btn">
            <i class="fas fa-shopping-cart"></i>
            <span>tambah ke keranjang</span>
         </button>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">belum ada produk yang ditambahkan!</p>';
         }
      ?>

   </div>

</section>


<!-- menu section ends -->


<!-- footer section starts  -->
<?php include __DIR__ . '/../app/partials/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="assets/js/script.js"></script>

</body>
</html>