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
    header('Location: manage_sports.php');
    exit();
}

$sport_id = intval($_GET['id']);

// Fshi eventin sportiv nga database
$stmt = $pdo->prepare("DELETE FROM sports_events WHERE id = ?");
if ($stmt->execute([$sport_id])) {
    $_SESSION['success'] = "Eventi sportiv u fshi me sukses!";
} else {
    $_SESSION['error'] = "Gabim gjatë fshirjes së eventit!";
}

while (ob_get_level()) {
    ob_end_clean();
}
header('Location: manage_sports.php');
exit();
?>