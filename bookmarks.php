<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/user_auth.php';
require_once 'includes/user_functions.php';

// Kërko login
requireUserLogin();

$page_title = "Programet e Mia të Bookmarked";
include 'includes/header.php';

$user_id = getUserID();
$bookmarks = getUserBookmarks($user_id);
?>

<section class="bookmarks-page py-5">
    <div class="container">
        <div class="page-header mb-5">
            <h1 class="page-title">Programet e Mia të Bookmarked</h1>
            <p class="page-subtitle">Të gjitha programet TV që i keni shënuar si të bookmarked</p>
        </div>

        <?php if (!empty($bookmarks)): ?>
            <div class="bookmarks-grid">
                <?php foreach($bookmarks as $show): ?>
                <div class="bookmark-card">
                    <div class="bookmark-image">
                        <?php if (!empty($show['image_path']) && file_exists($show['image_path'])): ?>
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($show['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($show['title']); ?>" 
                                 loading="lazy">
                        <?php else: ?>
                            <div class="show-image-placeholder">
                                <i class="fas fa-tv"></i>
                            </div>
                        <?php endif; ?>
                        
                        <button class="btn-remove-bookmark" 
                                data-show-id="<?php echo $show['id']; ?>"
                                title="Hiq nga bookmark">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="bookmark-content">
                        <h3><?php echo htmlspecialchars($show['title']); ?></h3>
                        
                        <?php if (!empty($show['description'])): ?>
                            <p><?php echo htmlspecialchars(substr($show['description'], 0, 100)); ?></p>
                        <?php endif; ?>
                        
                        <div class="bookmark-meta">
                            <?php if (!empty($show['show_day']) && !empty($show['show_time'])): ?>
                            <span class="show-schedule">
                                <i class="fas fa-clock me-1"></i>
                                <?php 
                                $day_names = [
                                    'Monday' => 'E Hënë',
                                    'Tuesday' => 'E Martë',
                                    'Wednesday' => 'E Mërkurë',
                                    'Thursday' => 'E Enjte',
                                    'Friday' => 'E Premte',
                                    'Saturday' => 'E Shtunë',
                                    'Sunday' => 'E Dielë'
                                ];
                                echo $day_names[$show['show_day']] . ' në ' . date('H:i', strtotime($show['show_time']));
                                ?>
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($show['season']) && !empty($show['episode'])): ?>
                            <span class="show-episode">
                                <i class="fas fa-play-circle me-1"></i>
                                Sezoni <?php echo intval($show['season']); ?>, Episodi <?php echo intval($show['episode']); ?>
                            </span>
                            <?php endif; ?>
                            
                            <span class="bookmark-date">
                                <i class="fas fa-bookmark me-1"></i>
                                Bookmarked më: <?php echo formatDate($show['bookmarked_at']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="fas fa-bookmark fa-4x text-muted"></i>
                </div>
                <h3 class="empty-title">Asnjë program i bookmarked</h3>
                <p class="empty-text text-muted mb-4">
                    Ju nuk keni asnjë program TV të shënuar si të bookmarked. 
                    Shkoni te faqja e programeve TV për të shënuar disa programe.
                </p>
                <a href="shows.php" class="btn btn-primary">
                    <i class="fas fa-tv me-2"></i>Shiko Programet TV
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ✅ JavaScript për User Interactions -->
<script src="<?php echo SITE_URL; ?>/assets/js/user.js"></script>

<?php include 'includes/footer.php'; ?>