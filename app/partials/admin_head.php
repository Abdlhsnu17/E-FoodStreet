<?php
/**
 * <head> bersama untuk semua halaman admin.
 *
 * Cara pakai — set $page_title sebelum include:
 *
 *    <?php $page_title = 'dashboard'; include __DIR__ . '/../../app/partials/admin_head.php'; ?>
 */
$page_title = $page_title ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($page_title) ?> &mdash; Admin AbdiBites</title>
   <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
