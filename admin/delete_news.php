<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Kontrollo nëse ka ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: manage_news.php');
    exit();
}

$news_id = intval($_GET['id']);

// Merr të dhënat e lajmit për të fshirë foton
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if ($news) {
    // Fshi foto-n nëse ekziston
    if ($news['image_path'] && file_exists('../' . $news['image_path'])) {
        unlink('../' . $news['image_path']);
    }
    
    // Fshi lajmin nga database
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    if ($stmt->execute([$news_id])) {
        $_SESSION['success'] = "Lajmi u fshi me sukses!";
    } else {
        $_SESSION['error'] = "Gabim gjatë fshirjes së lajmit!";
    }
} else {
    $_SESSION['error'] = "Lajmi nuk u gjet!";
}

header('Location: manage_news.php');
exit();
?>