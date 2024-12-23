<?php
session_start(); 

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}elseif ($_SESSION['role'] == 'admin') {
    header('Location: dashbordadmin.php');
    exit();
}elseif ($_SESSION['role'] == 'user') {
    header('Location: dashbordmhs.php');
    exit();
}elseif ($_SESSION['role'] == 'dokter') {
    header('Location: dashborddokter.php');
    exit();
}
?>