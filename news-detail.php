<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
// MOS përfshij user_auth.php këtu - është tashmë në header

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$news_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    header('Location: index.php');
    exit();
}

$page_title = htmlspecialchars($news['title']);
include 'includes/header.php';

// ✅ Shto në historinë e leximit nëse useri është i loguar
if (isUserLoggedIn()) {
    // Përfshij user_functions.php për këtë funksion
    require_once 'includes/user_functions.php';
    addToReadingHistory(getUserID(), $news_id);
}

// ✅ Merr numrin e like-ave dhe komentet
require_once 'includes/user_functions.php';
$likes_count = getLikesCount($news_id);
$comments = getNewsComments($news_id);

// ✅ Kontrollo nëse useri e ka këtë lajm të preferuar
$is_favorite = false;
$user_like = null;
if (isUserLoggedIn()) {
    $is_favorite = isNewsFavorite(getUserID(), $news_id);
    $user_like = getUserLike(getUserID(), $news_id);
}
?>

<section class="news-detail">
    <div class="container">
        <article class="news-article">
            <h1><?php echo htmlspecialchars($news['title']); ?></h1>
            
            <div class="news-meta">
                <span class="news-date">
                    <i class="fas fa-calendar me-2"></i>
                    <?php echo isset($news['created_at']) ? formatDate($news['created_at']) : 'N/A'; ?>
                </span>
                <span class="news-category"><?php echo ucfirst(htmlspecialchars($news['category'] ?? 'Të Tjera')); ?></span>
                <span class="likes-count">
                    <i class="fas fa-heart me-1"></i><?php echo $likes_count; ?> Like
                </span>
            </div>
            
            <?php if(!empty($news['image_path']) && file_exists($news['image_path'])): ?>
            <div class="news-image-full">
                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($news['image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($news['title']); ?>">
            </div>
            <?php endif; ?>
            
            <div class="news-description">
                <p><strong><?php echo htmlspecialchars($news['description']); ?></strong></p>
            </div>
            
            <div class="news-content">
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>
            
            <!-- ✅ SEKSIONI I RI PËR INTERAKSION -->
            <div class="news-interactions mt-4">
                <div class="interaction-buttons">
                    <!-- Like Button -->
                    <button class="btn btn-outline-danger like-btn <?php echo $user_like ? 'liked' : ''; ?>" 
                            data-news-id="<?php echo $news_id; ?>">
                        <i class="fas fa-heart me-2"></i>
                        <span class="like-text"><?php echo $user_like ? 'Pëlqyer' : 'Pëlqej'; ?></span>
                        <span class="likes-count-badge"><?php echo $likes_count; ?></span>
                    </button>
                    
                    <!-- Favorite Button -->
                    <?php if (isUserLoggedIn()): ?>
                    <button class="btn btn-outline-warning favorite-btn <?php echo $is_favorite ? 'favorited' : ''; ?>" 
                            data-news-id="<?php echo $news_id; ?>">
                        <i class="fas fa-star me-2"></i>
                        <span class="favorite-text"><?php echo $is_favorite ? 'E Preferuar' : 'Shto në Favorite'; ?></span>
                    </button>
                    <?php else: ?>
                    <a href="login.php" class="btn btn-outline-warning">
                        <i class="fas fa-star me-2"></i>Login për Favorite
                    </a>
                    <?php endif; ?>
                    
                    <!-- Share Button -->
                    <button class="btn btn-outline-info share-btn" data-news-id="<?php echo $news_id; ?>">
                        <i class="fas fa-share-alt me-2"></i>Shpërndaj
                    </button>
                </div>
            </div>
            
            <!-- ✅ SEKSIONI I RI PËR KOMENTE -->
            <div class="comments-section mt-5">
                <h3 class="mb-4">
                    <i class="fas fa-comments me-2"></i>Komentet 
                    <span class="badge bg-secondary"><?php echo count($comments); ?></span>
                </h3>
                
                <?php if (isUserLoggedIn()): ?>
                <div class="comment-form mb-4">
                    <form id="add-comment-form" data-news-id="<?php echo $news_id; ?>">
                        <div class="mb-3">
                            <textarea class="form-control" name="comment_text" placeholder="Shkruaj komentin tënd..." rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Posto Koment
                        </button>
                    </form>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <a href="login.php" class="alert-link">Hyr në llogari</a> për të shkruar komente.
                </div>
                <?php endif; ?>
                
                <div class="comments-list" id="comments-list">
                    <?php if (!empty($comments)): ?>
                        <?php foreach($comments as $comment): ?>
                        <div class="comment-item mb-3" data-comment-id="<?php echo $comment['id']; ?>">
                            <div class="d-flex">
                                <div class="comment-avatar me-3">
                                    <?php if (!empty($comment['profile_image'])): ?>
                                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($comment['profile_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($comment['username']); ?>" 
                                             class="rounded-circle" width="40" height="40">
                                    <?php else: ?>
                                        <div class="avatar-placeholder rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="comment-content flex-grow-1">
                                    <div class="comment-header d-flex justify-content-between align-items-center mb-2">
                                        <strong class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></strong>
                                        <small class="comment-date text-muted">
                                            <?php echo formatDate($comment['created_at']); ?>
                                        </small>
                                    </div>
                                    <div class="comment-text">
                                        <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                                    </div>
                                    <div class="comment-actions mt-2">
                                        <button class="btn btn-sm btn-outline-secondary reply-btn" 
                                                data-comment-id="<?php echo $comment['id']; ?>">
                                            <i class="fas fa-reply me-1"></i>Përgjigju
                                        </button>
                                    </div>
                                    
                                    <!-- Reply Form (hidden by default) -->
                                    <div class="reply-form mt-3" style="display: none;">
                                        <form class="add-reply-form" data-parent-id="<?php echo $comment['id']; ?>">
                                            <div class="mb-2">
                                                <textarea class="form-control form-control-sm" name="reply_text" placeholder="Shkruaj përgjigjen..." rows="2" required></textarea>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-paper-plane me-1"></i>Posto Përgjigje
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm cancel-reply">
                                                    <i class="fas fa-times me-1"></i>Anulo
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Replies -->
                                    <?php 
                                    $replies = getCommentReplies($comment['id']);
                                    if (!empty($replies)): 
                                    ?>
                                    <div class="replies mt-3 ms-4">
                                        <?php foreach($replies as $reply): ?>
                                        <div class="reply-item mb-2 pb-2 border-start border-3 ps-3">
                                            <div class="d-flex">
                                                <div class="reply-avatar me-2">
                                                    <?php if (!empty($reply['profile_image'])): ?>
                                                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($reply['profile_image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($reply['username']); ?>" 
                                                             class="rounded-circle" width="30" height="30">
                                                    <?php else: ?>
                                                        <div class="avatar-placeholder rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" 
                                                             style="width: 30px; height: 30px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="reply-content">
                                                    <div class="reply-header d-flex justify-content-between align-items-center mb-1">
                                                        <strong class="reply-author small"><?php echo htmlspecialchars($reply['username']); ?></strong>
                                                        <small class="reply-date text-muted">
                                                            <?php echo formatDate($reply['created_at']); ?>
                                                        </small>
                                                    </div>
                                                    <div class="reply-text small">
                                                        <?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comments fa-2x mb-3"></i>
                            <p>Asnjë koment ende. Bëhu i pari që komenton!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="news-actions mt-4">
                <a href="news.php" class="btn btn-danger">
                    <i class="fas fa-arrow-left me-2"></i>Kthehu te Lajmet
                </a>
            </div>
        </article>
    </div>
</section>

<!-- ✅ JavaScript për User Interactions -->
<script src="<?php echo SITE_URL; ?>/assets/js/user.js"></script>

<?php include 'includes/footer.php'; ?>