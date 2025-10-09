<?php
// File: config.php
// Cấu hình database và các thông số cơ bản

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'spa_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'Suối Spa');
define('SITE_URL', 'http://localhost/spa/');

// Function để kết nối database
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        // Log error or handle appropriately
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}

// Function để validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Function để hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function để verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>