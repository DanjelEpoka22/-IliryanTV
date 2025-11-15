<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Kontrollo nëse ka ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: manage_shows.php');
    exit();
}

$show_id = intval($_GET['id']);

// Fshi programin nga database
$stmt = $pdo->prepare("DELETE FROM tv_shows WHERE id = ?");
if ($stmt->execute([$show_id])) {
    $_SESSION['success'] = "Programi TV u fshi me sukses!";
} else {
    $_SESSION['error'] = "Gabim gjatë fshirjes së programit!";
}

while (ob_get_level()) {
    ob_end_clean();
}
header('Location: manage_shows.php');
exit();
?>