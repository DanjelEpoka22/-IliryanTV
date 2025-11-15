<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/user_auth.php';
require_once 'includes/user_functions.php';

// Kërko login
requireUserLogin();

$page_title = "Lajmet e Mia të Preferuara";
include 'includes/header.php';

$user_id = getUserID();
$favorites = getUserFavorites($user_id);
?>

<section class="favorites-page py-5">
    <div class="container">
        <div class="page-header mb-5">
            <h1 class="page-title">Lajmet e Mia të Preferuara</h1>
            <p class="page-subtitle">Të gjitha lajmet që i keni shënuar si të preferuara</p>
        </div>

        <?php if (!empty($favorites)): ?>
            <div class="favorites-grid">
                <?php foreach($favorites as $news): ?>
                <div class="favorite-card">
                    <div class="favorite-image">
                        <?php if (!empty($news['image_path']) && file_exists($news['image_path'])): ?>
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($news['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                 loading="lazy">
                        <?php else: ?>
                            <div class="news-image-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        
                        <button class="btn-remove-favorite" 
                                data-news-id="<?php echo $news['id']; ?>"
                                title="Hiq nga favorite">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="favorite-content">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($news['description'], 0, 120)); ?></p>
                        
                        <div class="favorite-meta">
                            <span class="news-date">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo formatDate($news['created_at']); ?>
                            </span>
                            <span class="favorite-date">
                                <i class="fas fa-star me-1"></i>
                                Shtuar më: <?php echo formatDate($news['favorited_at']); ?>
                            </span>
                        </div>
                        
                        <div class="favorite-actions">
                            <a href="news-detail.php?id=<?php echo $news['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Shiko
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="fas fa-heart fa-4x text-muted"></i>
                </div>
                <h3 class="empty-title">Asnjë lajm i preferuar</h3>
                <p class="empty-text text-muted mb-4">
                    Ju nuk keni asnjë lajm të shënuar si të preferuar. 
                    Shkoni te faqja e lajmeve për të shënuar disa lajme si të preferuar.
                </p>
                <a href="news.php" class="btn btn-primary">
                    <i class="fas fa-newspaper me-2"></i>Shiko Lajmet
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ✅ JavaScript për User Interactions -->
<script src="<?php echo SITE_URL; ?>/assets/js/user.js"></script>

<?php include 'includes/footer.php'; ?>