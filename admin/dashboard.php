<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Admin Dashboard";
include '../includes/header.php';

// Get stats
$news_count = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$sports_count = $pdo->query("SELECT COUNT(*) FROM sports_events")->fetchColumn();
$shows_count = $pdo->query("SELECT COUNT(*) FROM tv_shows")->fetchColumn();
$featured_news_count = $pdo->query("SELECT COUNT(*) FROM news WHERE featured = 1")->fetchColumn();

// Get recent news
$recent_news = getLatestNews($pdo, 5);
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_news.php"><i class="fas fa-newspaper"></i> Menaxho Lajmet</a></li>
            <li><a href="add_news.php"><i class="fas fa-plus"></i> Shto Lajm</a></li>
            <li><a href="manage_sports.php"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="add_sports.php"><i class="fas fa-plus"></i> Shto Event</a></li>
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="add_shows.php"><i class="fas fa-plus"></i> Shto Program</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <div class="welcome-message">
                Mirë se vini, <strong><?php echo $_SESSION['admin_username']; ?></strong>!
            </div>
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
        <div class="quick-actions">
            <h2>Veprime të Shpejta</h2>
            <div class="actions-grid">
                <a href="add_news.php" class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Shto Lajm të Ri</span>
                </a>
                <a href="add_sports.php" class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Shto Event Sportiv</span>
                </a>
                <a href="add_shows.php" class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Shto Program TV</span>
                </a>
                <a href="manage_news.php" class="action-card">
                    <i class="fas fa-list"></i>
                    <span>Shiko të Gjitha Lajmet</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <div class="activity-header">
                <h2>Lajmet e Fundit</h2>
                <a href="manage_news.php" class="btn btn-primary">Shiko të Gjitha</a>
            </div>
            
            <?php if (!empty($recent_news)): ?>
                <div class="activity-list">
                    <?php foreach($recent_news as $news): ?>
                    <div class="activity-item">
                        <div class="activity-content">
                            <h4><?php echo $news['title']; ?></h4>
                            <div class="activity-meta">
                                <span class="activity-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo formatDate($news['created_at']); ?>
                                </span>
                                <span class="activity-category">
                                    <i class="fas fa-tag"></i>
                                    <?php echo ucfirst($news['category']); ?>
                                </span>
                                <?php if ($news['featured']): ?>
                                    <span class="featured-badge">Kryesore</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <a href="edit_news.php?id=<?php echo $news['id']; ?>" class="btn btn-edit btn-small">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="../news-detail.php?id=<?php echo $news['id']; ?>" class="btn btn-view btn-small" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-activity">
                    <p>Asnjë lajm në database. <a href="add_news.php">Shto lajm të parë</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>