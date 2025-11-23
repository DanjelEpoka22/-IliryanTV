<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/user_auth.php'; // ✅ I RI
require_once 'includes/user_functions.php'; // ✅ I RI

$page_title = "Programet TV";
include 'includes/header.php';
?>

<!-- Albanian Culture Banner -->
<section class="albanian-culture-banner">
    <div class="container">
        <div class="banner-content">
            <h3>
                <i class="fas fa-tv me-2"></i>Programet e Traditave Tona
            </h3>
            <p>Shikoni programet tona që promovojnë kulturën dhe traditat shqiptare</p>
        </div>
    </div>
</section>

<?php

// Merr programet TV
$stmt = $pdo->prepare("SELECT * FROM tv_shows ORDER BY show_day, show_time");
$stmt->execute();
$tv_shows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grupo programet sipas ditëve
$shows_by_day = [];
$days_order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

foreach ($tv_shows as $show) {
    $day = $show['show_day'];
    if (!isset($shows_by_day[$day])) {
        $shows_by_day[$day] = [];
    }
    $shows_by_day[$day][] = $show;
}

// Merr lajmet për programet TV
$shows_news = getNewsByCategory($pdo, 'shows', 6);
?>

<section class="shows-page py-5">
    <div class="container">
        <h1 class="page-title">Programet TV</h1>
        
        <!-- Orari i Programeve -->
        <div class="tv-schedule">
            <div class="section-header">
                <h2 class="section-title">Orari Javor i Programeve</h2>
                <p class="section-subtitle">Shiko programin e plotë të javës</p>
            </div>
            
            <?php if (!empty($shows_by_day)): ?>
                <div class="schedule-days">
                    <?php foreach($days_order as $day): ?>
                        <?php if (isset($shows_by_day[$day])): ?>
                        <div class="schedule-day">
                            <h3><?php 
                                $day_names = [
                                    'Monday' => 'E Hënë',
                                    'Tuesday' => 'E Martë',
                                    'Wednesday' => 'E Mërkurë',
                                    'Thursday' => 'E Enjte',
                                    'Friday' => 'E Premte',
                                    'Saturday' => 'E Shtunë',
                                    'Sunday' => 'E Dielë'
                                ];
                                echo $day_names[$day];
                            ?></h3>
                            
                            <div class="shows-list">
                                <?php foreach($shows_by_day[$day] as $show): 
                                    // ✅ Kontrollo nëse programi është bookmarked
                                    $is_bookmarked = false;
                                    if (isUserLoggedIn()) {
                                        $is_bookmarked = isShowBookmarked(getUserID(), $show['id']);
                                    }
                                ?>
                                <div class="show-item">
                                    <div class="show-time">
                                        <?php echo date('H:i', strtotime($show['show_time'])); ?>
                                    </div>
                                    <div class="show-info">
                                        <div class="show-header">
                                            <h4><?php echo htmlspecialchars($show['title']); ?></h4>
                                            <!-- ✅ BOOKMARK BUTTON -->
                                            <?php if (isUserLoggedIn()): ?>
                                            <button class="btn-bookmark <?php echo $is_bookmarked ? 'bookmarked' : ''; ?>" 
                                                    data-show-id="<?php echo $show['id']; ?>"
                                                    title="<?php echo $is_bookmarked ? 'Hiq bookmark' : 'Shto në bookmark'; ?>">
                                                <i class="fas fa-bookmark"></i>
                                            </button>
                                            <?php else: ?>
                                            <a href="login.php" class="btn-bookmark" title="Login për bookmark">
                                                <i class="far fa-bookmark"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($show['description'])): ?>
                                            <p><?php echo htmlspecialchars(substr($show['description'], 0, 80)); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($show['season']) && !empty($show['episode'])): ?>
                                            <span class="show-meta">
                                                <i class="fas fa-play-circle me-1"></i>
                                                Sezoni <?php echo intval($show['season']); ?>, Episodi <?php echo intval($show['episode']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-shows">
                    <i class="fas fa-tv fa-3x mb-3" style="color: #999;"></i>
                    <p>Asnjë program TV në këtë kohë.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Lajmet për Programet -->
        <div class="shows-news-section">
            <div class="section-header">
                <h2 class="section-title">Lajme për Programet TV</h2>
                <p class="section-subtitle">Zbuloni më shumë për programet tuaja të preferuara</p>
            </div>
            
            <div class="news-grid">
                <?php if (!empty($shows_news)): ?>
                    <?php foreach($shows_news as $news): 
                        // ✅ Kontrollo nëse useri e ka këtë lajm të preferuar
                        $is_favorite = false;
                        if (isUserLoggedIn()) {
                            $is_favorite = isNewsFavorite(getUserID(), $news['id']);
                        }
                    ?>
                    <div class="news-card">
                        <div class="news-image">
                            <?php if(!empty($news['image_path']) && file_exists($news['image_path'])): ?>
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($news['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                     loading="lazy">
                            <?php else: ?>
                                <div class="news-image-placeholder">
                                    <i class="fas fa-tv"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- ✅ FAVORITE BUTTON -->
                            <?php if (isUserLoggedIn()): ?>
                            <button class="btn-favorite <?php echo $is_favorite ? 'favorited' : ''; ?>" 
                                    data-news-id="<?php echo $news['id']; ?>"
                                    title="<?php echo $is_favorite ? 'Hiq nga favorite' : 'Shto në favorite'; ?>">
                                <i class="fas fa-star"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="news-content">
                            <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($news['description'], 0, 120)); ?></p>
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo formatDate($news['created_at']); ?>
                                </span>
                            </div>
                            <a href="news-detail.php?id=<?php echo intval($news['id']); ?>" class="read-more">
                                Lexo më shumë <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-news">
                        <i class="fas fa-newspaper fa-3x mb-3" style="color: #999;"></i>
                        <p>Asnjë lajm për programet TV në këtë kohë.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ✅ JavaScript për User Interactions -->
<script src="<?php echo SITE_URL; ?>/assets/js/user.js"></script>

<?php include 'includes/footer.php'; ?>