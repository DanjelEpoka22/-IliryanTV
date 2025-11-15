<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php'; // Tani është e sigurt sepse nuk ka funksione të dyfishta

requireAdminAuth();

$page_title = "Dashboard";
include 'includes/admin_header.php';


// Get stats
$news_count = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$sports_count = $pdo->query("SELECT COUNT(*) FROM sports_events")->fetchColumn();
$shows_count = $pdo->query("SELECT COUNT(*) FROM tv_shows")->fetchColumn();
$featured_news_count = $pdo->query("SELECT COUNT(*) FROM news WHERE featured = 1")->fetchColumn();

// Get recent news
$recent_news = getLatestNews($pdo, 5);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="add_news.php" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Shto Lajm
            </a>
            <a href="add_sports.php" class="btn btn-sm btn-success">
                <i class="fas fa-plus me-1"></i>Shto Event
            </a>
        </div>
    </div>
</div>

<div class="welcome-message alert alert-info">
    <i class="fas fa-user me-2"></i>Mirë se vini, <strong><?php echo $_SESSION['admin_username']; ?></strong>!
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $news_count; ?></h3>
            <p>Lajme Total</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $featured_news_count; ?></h3>
            <p>Lajme Kryesore</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-football-ball"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $sports_count; ?></h3>
            <p>Evente Sportive</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-tv"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $shows_count; ?></h3>
            <p>Programe TV</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Veprime të Shpejta
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="add_news.php" class="btn btn-primary w-100 h-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                            Shto Lajm të Ri
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="add_sports.php" class="btn btn-success w-100 h-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                            Shto Event Sportiv
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="add_shows.php" class="btn btn-info w-100 h-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                            Shto Program TV
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage_news.php" class="btn btn-warning w-100 h-100 py-3">
                            <i class="fas fa-list fa-2x mb-2"></i><br>
                            Shiko të Gjitha Lajmet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Lajmet e Fundit
                </h5>
                <a href="manage_news.php" class="btn btn-primary btn-sm">Shiko të Gjitha</a>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_news)): ?>
                    <div class="list-group">
                        <?php foreach($recent_news as $news): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo $news['title']; ?></h6>
                                    <div class="d-flex gap-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo formatDate($news['created_at']); ?>
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1"></i>
                                            <?php echo ucfirst($news['category']); ?>
                                        </small>
                                        <?php if ($news['featured']): ?>
                                            <span class="badge bg-warning">Kryesore</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <a href="edit_news.php?id=<?php echo $news['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="../news-detail.php?id=<?php echo $news['id']; ?>" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Asnjë lajm në database.</p>
                        <a href="add_news.php" class="btn btn-primary">Shto lajm të parë</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
// Përfundimi i faqes së adminit
echo '</main></div></div></body></html>';
?>