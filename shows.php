<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Programet TV";
include 'includes/header.php';

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
$shows_news = getNewsByCategory($pdo, 'shows', 5);
?>

<section class="shows-page">
    <div class="container">
        <h1 class="page-title">Programet TV</h1>
        
        <!-- Orari i Programeve -->
        <div class="tv-schedule">
            <h2 class="section-title">Orari Javor i Programeve</h2>
            
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
                                <?php foreach($shows_by_day[$day] as $show): ?>
                                <div class="show-item">
                                    <div class="show-time">
                                        <?php echo date('H:i', strtotime($show['show_time'])); ?>
                                    </div>
                                    <div class="show-info">
                                        <h4><?php echo $show['title']; ?></h4>
                                        <?php if ($show['description']): ?>
                                            <p><?php echo $show['description']; ?></p>
                                        <?php endif; ?>
                                        <?php if ($show['season'] && $show['episode']): ?>
                                            <span class="show-meta">Sezoni <?php echo $show['season']; ?>, Episodi <?php echo $show['episode']; ?></span>
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
                    <p>Asnjë program TV në këtë kohë.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Lajmet për Programet -->
        <div class="shows-news-section">
            <h2 class="section-title">Lajme për Programet TV</h2>
            <div class="news-grid">
                <?php if (!empty($shows_news)): ?>
                    <?php foreach($shows_news as $news): ?>
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
                        <p>Asnjë lajm për programet TV në këtë kohë.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>