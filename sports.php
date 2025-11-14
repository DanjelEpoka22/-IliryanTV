<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Sporti";
include 'includes/header.php';

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
                <p class="section-subtitle">Ndiqni të gjitha ngjarjet sportive të rëndësishme</p>
            </div>
            
            <div class="events-grid">
                <?php if (!empty($sports_events)): ?>
                    <?php foreach($sports_events as $event): ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <span class="event-status <?php echo htmlspecialchars($event['status']); ?>">
                                <?php 
                                $status_labels = [
                                    'upcoming' => 'Në vijim',
                                    'live' => '🔴 Live',
                                    'finished' => 'Përfunduar'
                                ];
                                echo $status_labels[$event['status']] ?? 'N/A';
                                ?>
                            </span>
                        </div>
                        
                        <div class="teams">
                            <div class="team">
                                <span class="team-name"><?php echo htmlspecialchars($event['team_a']); ?></span>
                                <?php if ($event['status'] === 'finished'): ?>
                                    <span class="score"><?php echo intval($event['score_a']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="vs">VS</div>
                            
                            <div class="team">
                                <span class="team-name"><?php echo htmlspecialchars($event['team_b']); ?></span>
                                <?php if ($event['status'] === 'finished'): ?>
                                    <span class="score"><?php echo intval($event['score_b']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="event-meta">
                            <span class="event-date">
                                <i class="fas fa-calendar me-2"></i>
                                <?php echo formatDate($event['event_date']); ?>
                            </span>
                            <?php if (!empty($event['description'])): ?>
                                <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-events">
                        <i class="fas fa-calendar-times fa-3x mb-3" style="color: #999;"></i>
                        <p>Asnjë event sportiv në këtë kohë.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Lajmet Sportive -->
        <div class="sports-news-section">
            <div class="section-header">
                <h2 class="section-title">Lajmet e Fundit Sportive</h2>
                <p class="section-subtitle">Mbani veten e informuar me lajmet më të freskëta sportive</p>
            </div>
            
            <div class="news-grid">
                <?php if (!empty($sports_news)): ?>
                    <?php foreach($sports_news as $news): ?>
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

<?php include 'includes/footer.php'; ?>