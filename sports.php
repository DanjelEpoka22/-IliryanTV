<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Sporti";
include 'includes/header.php';
?>

<!-- Albanian Culture Banner -->
<section class="albanian-culture-banner">
    <div class="container">
        <div class="banner-content">
            <h3>
                <i class="fas fa-trophy me-2"></i>Sukseset e Kombit Tonë
            </h3>
            <p>Ndiqni eventet sportive dhe sukseset e atletëve shqiptarë në skenën ndërkombëtare</p>
        </div>
    </div>
</section>

<?php

// Përfshij user functions VETËM nëse ekzistojnë
if (file_exists('includes/user_functions.php')) {
    require_once 'includes/user_functions.php';
}

// Merr eventet sportive
$stmt = $pdo->prepare("SELECT * FROM sports_events ORDER BY event_date DESC");
$stmt->execute();
$sports_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Merr lajmet sportive
$sports_news = getNewsByCategory($pdo, 'sports', 6);
?>

<section class="sports-page py-5">
    <div class="container">
        <h1 class="page-title">Sporti</h1>
        
        <!-- Eventet Sportive -->
        <div class="sports-section">
            <div class="section-header">
                <h2 class="section-title">Eventet Sportive</h2>
                <p class="section-subtitle">Ndeshjet dhe turneunet e ardhshme</p>
            </div>
            
            <?php if (!empty($sports_events)): ?>
                <div class="events-grid">
                    <?php foreach($sports_events as $event): 
                        // Përcakto statusin e eventit
                        $status_class = '';
                        $status_text = '';
                        switch($event['status']) {
                            case 'upcoming':
                                $status_class = 'upcoming';
                                $status_text = 'Në vijim';
                                break;
                            case 'live':
                                $status_class = 'live';
                                $status_text = 'Në drejtim të përditshëm';
                                break;
                            case 'finished':
                                $status_class = 'finished';
                                $status_text = 'Përfunduar';
                                break;
                            default:
                                $status_class = 'upcoming';
                                $status_text = 'Në vijim';
                        }
                    ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <span class="event-status <?php echo $status_class; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </div>
                        
                        <div class="teams">
                            <div class="team">
                                <span class="team-name"><?php echo htmlspecialchars($event['team_a']); ?></span>
                                <?php if($event['status'] === 'finished'): ?>
                                    <span class="score"><?php echo intval($event['score_a']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <span class="vs">VS</span>
                            
                            <div class="team">
                                <span class="team-name"><?php echo htmlspecialchars($event['team_b']); ?></span>
                                <?php if($event['status'] === 'finished'): ?>
                                    <span class="score"><?php echo intval($event['score_b']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="event-meta">
                            <div class="event-date">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo formatDate($event['event_date']); ?>
                            </div>
                            
                            <?php if(!empty($event['description'])): ?>
                                <p class="event-description">
                                    <?php echo htmlspecialchars(substr($event['description'], 0, 120)); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-events">
                    <i class="fas fa-trophy fa-3x mb-3" style="color: #999;"></i>
                    <p>Asnjë event sportiv në këtë kohë.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Lajmet Sportive -->
        <div class="sports-news-section">
            <div class="section-header">
                <h2 class="section-title">Lajmet e Fundit Sportive</h2>
                <p class="section-subtitle">Mbani veten e informuar me lajmet më të freskëta sportive</p>
            </div>
            
            <div class="news-grid">
                <?php if (!empty($sports_news)): ?>
                    <?php foreach($sports_news as $news): 
                        // ✅ Kontrollo nëse useri e ka këtë lajm të preferuar - ME KONTROLL
                        $is_favorite = false;
                        if (isUserLoggedIn() && function_exists('isNewsFavorite')) {
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
                                    <i class="fas fa-football-ball"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- ✅ FAVORITE BUTTON -->
                            <?php if (isUserLoggedIn() && function_exists('isNewsFavorite')): ?>
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
                        <p>Asnjë lajm sportiv në këtë kohë.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ✅ JavaScript për User Interactions -->
<?php if (file_exists('assets/js/user.js')): ?>
<script src="<?php echo SITE_URL; ?>/assets/js/user.js"></script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>