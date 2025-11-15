<?php
// Start session dhe përfshij të gjitha skedarët e nevojshëm
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/config.php';

// Kontrollo nëse kërkesa është POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metoda e pavlefshme']);
    exit;
}

// Përfshij user authentication
require_once 'includes/user_auth.php';

// Kontrollo nëse useri është i loguar
if (!isUserLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Kërkohet login']);
    exit;
}

// Përfshij user functions
require_once 'includes/user_functions.php';

$user_id = getUserID();
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'like':
            handleLikeAction($user_id);
            break;
            
        case 'favorite':
            handleFavoriteAction($user_id);
            break;
            
        case 'bookmark':
            handleBookmarkAction($user_id);
            break;
            
        case 'notification':
            handleNotificationAction($user_id);
            break;
            
        case 'comment':
            handleCommentAction($user_id);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Veprim i pavlefshëm: ' . $action]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Gabim në server: ' . $e->getMessage()]);
}

function handleLikeAction($user_id) {
    $news_id = intval($_POST['news_id'] ?? 0);
    $sub_action = $_POST['action'] ?? 'add'; // Kjo është problem!
    
    // RREGULLO: përdor 'sub_action' ose 'like_action' në vend të 'action'
    $like_action = $_POST['sub_action'] ?? $_POST['like_action'] ?? 'add';
    
    if ($news_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID e pavlefshme lajmi']);
        return;
    }
    
    global $pdo;
    
    if ($like_action === 'add') {
        // Kontrollo nëse ekziston tashmë
        $stmt = $pdo->prepare("SELECT id FROM news_likes WHERE user_id = ? AND news_id = ?");
        $stmt->execute([$user_id, $news_id]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO news_likes (user_id, news_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $news_id]);
        }
    } else {
        $stmt = $pdo->prepare("DELETE FROM news_likes WHERE user_id = ? AND news_id = ?");
        $stmt->execute([$user_id, $news_id]);
    }
    
    // Merr numrin e përditësuar të like-ave
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM news_likes WHERE news_id = ?");
    $stmt->execute([$news_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'likes_count' => $result['count'] ?? 0
    ]);
}

function handleFavoriteAction($user_id) {
    $news_id = intval($_POST['news_id'] ?? 0);
    $action_type = $_POST['action'] ?? 'add';
    
    if ($news_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID e pavlefshme lajmi']);
        return;
    }
    
    if ($action_type === 'add') {
        $success = addToFavorites($user_id, $news_id);
    } else {
        $success = removeFromFavorites($user_id, $news_id);
    }
    
    echo json_encode(['success' => $success]);
}

function handleBookmarkAction($user_id) {
    $show_id = intval($_POST['show_id'] ?? 0);
    $action_type = $_POST['action'] ?? 'add';
    
    if ($show_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID e pavlefshme programi']);
        return;
    }
    
    if ($action_type === 'add') {
        $success = addBookmark($user_id, $show_id);
    } else {
        $success = removeBookmark($user_id, $show_id);
    }
    
    echo json_encode(['success' => $success]);
}

function handleNotificationAction($user_id) {
    // Implementimi i notifikimeve do të shtohet më vonë
    echo json_encode(['success' => true, 'message' => 'Funksionaliteti i notifikimeve do të implementohet së shpejti']);
}

function handleCommentAction($user_id) {
    $news_id = intval($_POST['news_id'] ?? 0);
    $comment_text = trim($_POST['comment_text'] ?? '');
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    
    if ($news_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID e pavlefshme lajmi']);
        return;
    }
    
    if (empty($comment_text)) {
        echo json_encode(['success' => false, 'message' => 'Komenti nuk mund të jetë bosh']);
        return;
    }
    
    global $pdo;
    
    // Shto komentin
    $stmt = $pdo->prepare("INSERT INTO news_comments (user_id, news_id, comment_text, parent_id) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$user_id, $news_id, $comment_text, $parent_id]);
    
    if ($success) {
        $comment_id = $pdo->lastInsertId();
        
        // Merr komentin e shtuar me të dhënat e userit
        $stmt = $pdo->prepare("
            SELECT nc.*, u.username, u.profile_image 
            FROM news_comments nc 
            JOIN users u ON nc.user_id = u.id 
            WHERE nc.id = ?
        ");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Format data
        $comment['created_at'] = date('d.m.Y H:i', strtotime($comment['created_at']));
        
        echo json_encode([
            'success' => true,
            'comment' => $comment
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gabim në ruajtjen e komentit']);
    }
}
?>