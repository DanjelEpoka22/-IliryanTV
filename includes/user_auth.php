<?php
// Nuk e nisim session këtu sepse tashmë është nisur në header

function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getUserID() {
    return $_SESSION['user_id'] ?? null;
}

function getUserUsername() {
    return $_SESSION['user_username'] ?? null;
}

function requireUserLogin() {
    if (!isUserLoggedIn()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit();
    }
}

function userLogin($user_id, $username) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_username'] = $username;
}

function userLogout() {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    // Mos e shkatërro session, sepse admini mund të jetë i loguar
}
?>