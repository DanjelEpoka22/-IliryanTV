<?php
function isAdminLoggedIn() {
    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }

    // Inactivity timeout (30 minutes)
    $timeout = 30 * 60;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        // Clear admin session vars but do not redirect here
        unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_logged_in'], $_SESSION['last_activity']);
        return false;
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
    return true;
}

function requireAdminAuth() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function adminLogin($username, $password, $pdo) {
    if (empty($username) || empty($password)) {
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Prevent session fixation
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        // Set session variables
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['last_activity'] = time();
        return true;
    }

    return false;
}

function adminLogout() {
    // Clear admin session variables
    unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_logged_in'], $_SESSION['last_activity']);

    // Destroy session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy session
    session_destroy();

    header('Location: login.php');
    exit();
}
?>