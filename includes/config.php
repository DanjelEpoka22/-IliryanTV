<?php
// Start session me konfigurime të sigurta
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 0, // session cookie (expires on browser close)
        'cookie_secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), // true only on HTTPS
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'iliryan_tv');

// Create connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Website configuration
define('SITE_NAME', 'IliryanTV');
define('SITE_URL', 'http://localhost/tv');
define('UPLOAD_PATH', 'assets/images/uploads/');
?>