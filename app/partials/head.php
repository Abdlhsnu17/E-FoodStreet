<?php
/**
 * <head> bersama untuk semua halaman pengunjung.
 *
 * Cara pakai — set $page_title sebelum include:
 *
 *    <?php $page_title = 'menu'; include __DIR__ . '/../app/partials/head.php'; ?>
 *
 * Set $use_swiper = true kalau halaman memakai slider (home & about).
 */
$page_title = $page_title ?? 'AbdiBites';
$use_swiper = $use_swiper ?? false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($page_title) ?> &mdash; AbdiBites</title>
   <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

   <?php if($use_swiper){ ?>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
   <?php } ?>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
