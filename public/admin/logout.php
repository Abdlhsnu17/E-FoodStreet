<?php

require __DIR__ . '/../../app/config/connect.php';

session_start();
session_unset();
session_destroy();

header('location:../login.php');

?>