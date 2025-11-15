<?php
require_once 'config.php';

// ✅ Funksione për Favorite
function addToFavorites($user_id, $news_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO user_favorite_news (user_id, news_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $news_id]);
    } catch (PDOException $e) {
        return false;
    }
}

function removeFromFavorites($user_id, $news_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM user_favorite_news WHERE user_id = ? AND news_id = ?");
    return $stmt->execute([$user_id, $news_id]);
}

function isNewsFavorite($user_id, $news_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM user_favorite_news WHERE user_id = ? AND news_id = ?");
    $stmt->execute([$user_id, $news_id]);
    return $stmt->fetch() !== false;
}

function getUserFavorites($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT n.*, ufn.created_at as favorited_at 
        FROM user_favorite_news ufn 
        JOIN news n ON ufn.news_id = n.id 
        WHERE ufn.user_id = ? 
        ORDER BY ufn.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ✅ Funksione për Bookmark
function addBookmark($user_id, $show_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO user_bookmarked_shows (user_id, show_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $show_id]);
    } catch (PDOException $e) {
        return false;
    }
}

function removeBookmark($user_id, $show_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM user_bookmarked_shows WHERE user_id = ? AND show_id = ?");
    return $stmt->execute([$user_id, $show_id]);
}

function isShowBookmarked($user_id, $show_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM user_bookmarked_shows WHERE user_id = ? AND show_id = ?");
    $stmt->execute([$user_id, $show_id]);
    return $stmt->fetch() !== false;
}

function getUserBookmarks($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT ts.*, ubs.created_at as bookmarked_at 
        FROM user_bookmarked_shows ubs 
        JOIN tv_shows ts ON ubs.show_id = ts.id 
        WHERE ubs.user_id = ? 
        ORDER BY ubs.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ✅ Funksione për Like
function addLike($user_id, $news_id, $reaction_type = 'like') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO news_likes (user_id, news_id, reaction_type) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $news_id, $reaction_type]);
    } catch (PDOException $e) {
        return false;
    }
}

function removeLike($user_id, $news_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM news_likes WHERE user_id = ? AND news_id = ?");
    return $stmt->execute([$user_id, $news_id]);
}

function getUserLike($user_id, $news_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM news_likes WHERE user_id = ? AND news_id = ?");
    $stmt->execute([$user_id, $news_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getLikesCount($news_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM news_likes WHERE news_id = ?");
    $stmt->execute([$news_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] ?? 0;
}

// ✅ Funksione për Komente
function addComment($user_id, $news_id, $comment_text, $parent_id = null) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO news_comments (user_id, news_id, comment_text, parent_id) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $news_id, $comment_text, $parent_id]);
}

function getNewsComments($news_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT nc.*, u.username, u.profile_image 
        FROM news_comments nc 
        JOIN users u ON nc.user_id = u.id 
        WHERE nc.news_id = ? AND nc.parent_id IS NULL 
        ORDER BY nc.created_at DESC
    ");
    $stmt->execute([$news_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCommentReplies($parent_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT nc.*, u.username, u.profile_image 
        FROM news_comments nc 
        JOIN users u ON nc.user_id = u.id 
        WHERE nc.parent_id = ? 
        ORDER BY nc.created_at ASC
    ");
    $stmt->execute([$parent_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ✅ Funksione për Historinë e Leximit
function addToReadingHistory($user_id, $news_id) {
    global $pdo;
    // Kontrollo nëse ekziston tashmë
    $stmt = $pdo->prepare("SELECT id FROM user_reading_history WHERE user_id = ? AND news_id = ?");
    $stmt->execute([$user_id, $news_id]);
    
    if ($stmt->fetch()) {
        // Update ekzistues
        $stmt = $pdo->prepare("UPDATE user_reading_history SET read_at = CURRENT_TIMESTAMP WHERE user_id = ? AND news_id = ?");
        return $stmt->execute([$user_id, $news_id]);
    } else {
        // Shto të ri
        $stmt = $pdo->prepare("INSERT INTO user_reading_history (user_id, news_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $news_id]);
    }
}
?>