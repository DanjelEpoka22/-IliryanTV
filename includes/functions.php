<?php
// Funksioni i ri redirect me output buffering
function redirect($url) {
    // Pastro çdo output buffer
    while (ob_get_level()) {
        ob_end_clean();
    }
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function getFeaturedNews($pdo, $limit = 5) {
    // For LIMIT with integers, we need to use intval() and concatenate
    $limit = intval($limit);
    $stmt = $pdo->prepare("SELECT * FROM news WHERE featured = 1 ORDER BY created_at DESC LIMIT " . $limit);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestNews($pdo, $limit = 10) {
    $limit = intval($limit);
    $stmt = $pdo->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT " . $limit);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNewsByCategory($pdo, $category, $limit = 10) {
    $limit = intval($limit);
    $stmt = $pdo->prepare("SELECT * FROM news WHERE category = ? ORDER BY created_at DESC LIMIT " . $limit);
    $stmt->execute([$category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// MOS I SHTO FUNKSIONET E ADMINIT KËTU - ato janë tashmë në auth.php
?>