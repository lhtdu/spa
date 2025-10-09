<?php
session_start();
require_once 'auth_functions.php';

// Sử dụng function logout
logoutUser();

// Hủy session
session_destroy();

// Chuyển hướng về trang chủ
header('Location: index.php');
exit();
?>