<?php
/**
 * Tombol kembali untuk halaman admin selain dashboard.
 *
 * Cara pakai — opsional set $back_link & $back_text sebelum include:
 *
 *    <?php $back_link = 'products.php'; $back_text = 'kembali ke produk';
 *          include __DIR__ . '/../../app/partials/admin_back.php'; ?>
 */
$back_link = $back_link ?? 'dashboard.php';
$back_text = $back_text ?? 'kembali ke dashboard';
?>

<div class="back-bar">
   <a href="<?= $back_link; ?>" class="back-btn">
      <i class="fas fa-arrow-left"></i>
      <span><?= $back_text; ?></span>
   </a>
</div>
