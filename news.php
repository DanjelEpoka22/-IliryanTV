<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Lajmet";
include 'includes/header.php';
?>

<!-- Albanian Culture Banner -->
<section class="albanian-culture-banner">
    <div class="container">
        <div class="banner-content">
            <h3>
                <i class="fas fa-newspaper me-2"></i>Lajmet e Kombit Tonë
            </h3>
            <p>Mbani veten e informuar me lajmet më të fundit që lidhen me Shqipërinë dhe shqiptarët</p>
        </div>
    </div>
</section>

<?php

// Përfshij user functions VETËM nëse ekzistojnë dhe nëse useri është i loguar
if (file_exists('includes/user_functions.php')) {
    require_once 'includes/user_functions.php';
}

try {
    // Get all news with pagination
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $per_page = 12;
    $offset = ($page - 1) * $per_page;

    // Validate page number
    if ($page < 1) {
        $page = 1;
        $offset = 0;
    }

    // Get news data with likes count
    $stmt = $pdo->prepare("
        SELECT n.*, 
               (SELECT COUNT(*) FROM news_likes WHERE news_id = n.id) as likes_count,
               (SELECT COUNT(*) FROM news_comments WHERE news_id = n.id) as comments_count
        FROM news n 
        ORDER BY n.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->bindParam(1, $per_page, PDO::PARAM_INT);
    $stmt->bindParam(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $all_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count
    $count_stmt = $pdo->query("SELECT COUNT(*) as total FROM news");
    $total_row = $count_stmt->fetch(PDO::FETCH_ASSOC);
    $total = $total_row['total'] ?? 0;
    $total_pages = $total > 0 ? ceil($total / $per_page) : 1;

    // Ensure page doesn't exceed total pages
    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
    }

} catch (Exception $e) {
    $all_news = [];
    $total_pages = 1;
    $page = 1;
    $error_message = "Gabim në ngarkim të lajmeve: " . $e->getMessage();
}
?>

<section class="news-page py-5">
    <div class="container">
        <h1 class="page-title">Të gjitha Lajmet</h1>
        
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="news-grid-full">
            <?php if (!empty($all_news)): ?>
                <?php foreach($all_news as $news): 
                    // ✅ Kontrollo nëse useri e ka këtë lajm të preferuar - ME KONTROLL PËR FUNKSIONIN
                    $is_favorite = false;
                    $user_like = null;
                    if (isUserLoggedIn() && function_exists('isNewsFavorite')) {
                        $is_favorite = isNewsFavorite(getUserID(), $news['id']);
                        $user_like = getUserLike(getUserID(), $news['id']);
                    }
                ?>
                <div class="news-card">
                    <div class="news-image">
                        <?php if (!empty($news['image_path']) && file_exists($news['image_path'])): ?>
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($news['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                 loading="lazy">
                        <?php else: ?>
                            <div class="news-image-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- ✅ BUTONAT E INTERAKSIONIT -->
                        <div class="news-actions-overlay">
                            <?php if (isUserLoggedIn() && function_exists('isNewsFavorite')): ?>
                            <button class="btn-favorite <?php echo $is_favorite ? 'favorited' : ''; ?>" 
                                    data-news-id="<?php echo $news['id']; ?>"
                                    title="<?php echo $is_favorite ? 'Hiq nga favorite' : 'Shto në favorite'; ?>">
                                <i class="fas fa-star"></i>
                            </button>
                            <?php endif; ?>
                            
                            <div class="news-stats">
                                <span class="likes-count" title="<?php echo $news['likes_count']; ?> pëlqime">
                                    <i class="fas fa-heart"></i> <?php echo $news['likes_count']; ?>
                                </span>
                                <span class="comments-count" title="<?php echo $news['comments_count']; ?> komente">
                                    <i class="fas fa-comment"></i> <?php echo $news['comments_count']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($news['description'], 0, 150)); ?></p>
                        <div class="news-meta">
                            <span class="news-date">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo isset($news['created_at']) ? formatDate($news['created_at']) : 'N/A'; ?>
                            </span>
                            <span class="news-category"><?php echo ucfirst(htmlspecialchars($news['category'] ?? 'Të Tjera')); ?></span>
                        </div>
                        <div class="news-footer">
                            <a href="news-detail.php?id=<?php echo intval($news['id']); ?>" class="read-more">
                                Lexo më shumë <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            
                            <!-- ✅ LIKE BUTTON -->
                            <?php if (isUserLoggedIn() && function_exists('getUserLike')): ?>
                            <button class="btn-like-sm <?php echo $user_like ? 'liked' : ''; ?>" 
                                    data-news-id="<?php echo $news['id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                            <?php else: ?>
                            <a href="login.php" class="btn-like-sm" title="Login për të pëlqyer">
                                <i class="fas fa-heart"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted fs-5">Asnjë lajm i disponueshëm në këtë kohë.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <!-- ... pagination code mbetet i njëjtë ... -->
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</section>

<!-- ✅ JavaScript për User Interactions -->
<?php if (file_exists('assets/js/user.js')): ?>
<script src="<?php echo SITE_URL; ?>/assets/js/user.js"></script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>