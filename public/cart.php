<?php

require __DIR__ . '/../app/config/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'item keranjang dihapus!';
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   // header('location:cart.php');
   $message[] = 'menghapus semua dari keranjang!';
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'jumlah keranjang diperbarui';
}

$grand_total = 0;

?>

<?php $page_title = 'keranjang'; include __DIR__ . '/../app/partials/head.php'; ?>

<!-- header section starts  -->
<?php include __DIR__ . '/../app/partials/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>keranjang belanja</h3>
   <p><a href="home.php">home</a> <span> / keranjang</span></p>
</div>

<!-- shopping cart section starts  -->

<section class="products">

   <h1 class="title">keranjang anda</h1>

   <div class="box-container">

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"></button>
         <img src="assets/uploads/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Rp. </span><?= number_format($fetch_cart['price'], 0, ',', '.'); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
         <div class="sub-total"> sub total : <span>Rp. <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">keranjang Anda kosong</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>total keranjang : <span>Rp. <?= number_format($grand_total, 0, ',', '.'); ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">lanjutkan ke pembayaran</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('delete all from cart?');">delete all</button>
      </form>
      <a href="menu.php" class="btn">lanjutkan memilih menu</a>
   </div>

</section>

<!-- shopping cart section ends -->

<!-- footer section starts  -->
<?php include __DIR__ . '/../app/partials/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="assets/js/script.js"></script>

</body>
</html>