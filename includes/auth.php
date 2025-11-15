<?php
// MOS E THIRR session_start() KËTU - është tashmë në header

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminAuth() {
    if (!isAdminLoggedIn()) {
        // Përdor output buffering për redirect
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: login.php');
        exit();
    }
}

function adminLogin($username, $password, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            return true;
        }
    } catch (PDOException $e) {
        error_log("Admin login error: " . $e->getMessage());
    }
    return false;
}

function adminLogout() {
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
}
?>