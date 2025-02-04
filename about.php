<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- custom css file credit  -->
   <link rel="stylesheet" href="css/credit.css">

   <!-- custom css file header -->
   <link rel="stylesheet" href="css/header.css">

   <!-- custom css file header -->
   <link rel="stylesheet" href="css/header.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>about us</h3>
   <p><a href="home.php">home</a> <span> / about</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-image.svg" alt="">
      </div>

      <div class="content">
         <h3>mengapa harus memilih kami?</h3>
         <p>Yummy Bites adalah pilihan terbaik untuk Anda yang menginginkan makanan lezat, berkualitas, dan sehat. Kami menghadirkan berbagai hidangan dengan bahan-bahan segar, cita rasa autentik, dan proses pembuatan higienis. Dengan pelayanan ramah, harga terjangkau, serta berbagai pilihan menu yang menggugah selera, Yummy Bites siap memberikan pengalaman kuliner terbaik untuk Anda. Nikmati kelezatan tanpa kompromi hanya di Yummy Bites!</p>
         <a href="menu.php" class="btn">menu kami</a>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->

<section class="steps">

   <h1 class="title">simple steps</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/step-4.png" alt="">
         <h3>Cara Memesan</h3>
         <p>Register (jika belum punya akun) / Login (jika sudah punya akun) -> Update Alamat pada menu Profile ->Jelajahi Menu -> Pilih Makanan Favorit -> Masukkan ke Keranjang -> Lanjut ke keranjang – klik tombol keranjang -> Lanjutkan ke Pembayaran –> pastikan Alamat Pengiriman sudah terisi -> Periksa kembali pesanan lalu pilih metode pembayaran dan klik tombol Place Order -> Konfirmasi & Tunggu Pesanan – Setelah pembayaran berhasil, pesanan akan segera diproses dan dikirim</p>
      </div>

      <div class="box">
         <img src="images/step-5.png" alt="">
         <h3>pengiriman</h3>
         <p>Terima kasih telah memesan di Yummy Bites! Setelah pembayaran Anda berhasil, pesanan akan segera diproses dan dikirimkan ke alamat yang Anda pilih. Kami pastikan makanan Anda akan tiba dengan cepat, dalam kondisi segar, dan siap dinikmati.</p>
      </div>

      <div class="box">
         <img src="images/step-6.png" alt="">
         <h3>nikmati hidangan</h3>
         <p>Selamat menikmati makanan lezat yang telah kami siapkan. Terima kasih telah memilih Yummy Bites, selamat menikmati setiap gigitan!</p>
      </div>

   </div>

</section>

<!-- steps section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="title">ulasan pelanggan</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <p>Pertama kali mencoba Yummy Bites dan saya sangat puas! Makanan datang tepat waktu, masih panas, dan rasanya luar biasa. Porsi yang besar dan harganya terjangkau. Pasti akan pesan lagi!.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>ronaldo</h3>
         </div>

         <div class="swiper-slide slide">
            <p>Pelayanan cepat dan ramah. Makanan yang saya pesan juga enak, hanya sedikit lebih pedas dari yang saya kira, tapi tetap nikmat. Pengiriman juga sangat tepat waktu. Terima kasih Yummy Bites!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>merry</h3>
         </div>

         <div class="swiper-slide slide">
            <p>Pesanan saya sampai dengan aman dan cepat! Menu favorit saya sangat menggugah selera dan pasti jadi langganan. Sangat puas dengan kualitas dan kecepatan pengirimannya.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>messi</h3>
         </div>

         <div class="swiper-slide slide">
            <p>Yummy Bites selalu jadi pilihan saya! Menu selalu konsisten enak, dan pengiriman cepat tanpa cacat. Harga juga sangat bersaing dengan kualitas yang diberikan. Highly recommended!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>neymar</h3>
         </div>

        <div class="swiper-slide slide">
            <p>Saya sangat puas dengan sistem pemesanannya yang cepat dan mudah digunakan. Antarmukanya ramah pengguna, dan saya bisa menyelesaikan pesanan hanya dalam beberapa klik!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Haland</h3>
         </div>

         <div class="swiper-slide slide">
            <p>Dengan harga yang saya bayar, saya merasa mendapatkan nilai yang sangat baik. Produk ini sebanding bahkan lebih baik dari merek lain yang lebih mahal!Saya sangat puas dengan pelayanan yang diberikan!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Lara</h3>
         </div> 

         <div class="swiper-slide slide">
            <p>Ini pertama kalinya saya mencoba Yummy Bites, dan saya sangat terkesan! Makanan yang saya pesan tiba dalam kondisi segar, dan rasanya benar-benar menggugah selera. Pasti akan pesan lagi!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ronaldinho</h3>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<!-- reviews section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->=

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>