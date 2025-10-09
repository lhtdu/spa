<?php
// File: auth_check.php  
// Kiểm tra authentication và các function liên quan

session_start();
require_once 'auth_functions.php';

// Function kiểm tra user đã đăng nhập chưa
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true;
}
?>