<?php

// Login admin sudah digabung ke halaman login biasa: semua akun ada di
// tabel `users`, dan login.php sendiri yang mengarahkan pemilik akun
// ber-is_admin ke dashboard. File ini disimpan supaya tautan dan bookmark
// lama tidak mati.

header('location:../login.php');
exit;

?>
