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
$sports_news = getNewsByCategory($pdo, 'sports', 5);
?>

<section class="sports-page">
    <div class="container">
        <h1 class="page-title">Sporti</h1>
        
        <!-- Eventet Sportive -->
        <div class="sports-section">
            <h2 class="section-title">Eventet Sportive</h2>
            <div class="events-grid">
                <?php if (!empty($sports_events)): ?>
                    <?php foreach($sports_events as $event): ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo $event['title']; ?></h3>
                            <span class="event-status <?php echo $event['status']; ?>">
                                <?php 
                                $status_labels = [
                                    'upcoming' => 'Në vijim',
                                    'live' => 'Live',
                                    'finished' => 'Përfunduar'
                                ];
                                echo $status_labels[$event['status']];
                                ?>
                            </span>
                        </div>
                        
                        <div class="teams">
                            <div class="team">
                                <span class="team-name"><?php echo $event['team_a']; ?></span>
                                <?php if ($event['status'] === 'finished'): ?>
                                    <span class="score"><?php echo $event['score_a']; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="vs">VS</div>
                            
                            <div class="team">
                                <span class="team-name"><?php echo $event['team_b']; ?></span>
                                <?php if ($event['status'] === 'finished'): ?>
                                    <span class="score"><?php echo $event['score_b']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="event-meta">
                            <span class="event-date">
                                <i class="fas fa-calendar"></i>
                                <?php echo formatDate($event['event_date']); ?>
                            </span>
                            <?php if ($event['description']): ?>
                                <p class="event-description"><?php echo $event['description']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-events">
                        <p>Asnjë event sportiv në këtë kohë.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Lajmet Sportive -->
        <div class="sports-news-section">
            <h2 class="section-title">Lajmet e Fundit Sportive</h2>
            <div class="news-grid">
                <?php if (!empty($sports_news)): ?>
                    <?php foreach($sports_news as $news): ?>
                    <div class="news-card">
                        <?php if($news['image_path']): ?>
                        <div class="news-image">
                            <img src="<?php echo SITE_URL . '/' . $news['image_path']; ?>" alt="<?php echo $news['title']; ?>">
                        </div>
                        <?php endif; ?>
                        <div class="news-content">
                            <h3><?php echo $news['title']; ?></h3>
                            <p><?php echo substr($news['description'], 0, 100); ?>...</p>
                            <span class="news-date"><?php echo formatDate($news['created_at']); ?></span>
                            <a href="news-detail.php?id=<?php echo $news['id']; ?>" class="read-more">Lexo më shumë</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-news">
                        <p>Asnjë lajm sportiv në këtë kohë.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>