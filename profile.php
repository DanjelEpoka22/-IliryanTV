<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/user_auth.php';
require_once 'includes/user_functions.php';

// Kërko login
requireUserLogin();

$page_title = "Profili Im";
include 'includes/header.php';

// Merr të dhënat e userit
$user_id = getUserID();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: logout.php');
    exit();
}

// Merr statistikat
$favorites_count = count(getUserFavorites($user_id));
$bookmarks_count = count(getUserBookmarks($user_id));

// Merr historinë e leximit
$history_stmt = $pdo->prepare("
    SELECT n.*, urh.read_at 
    FROM user_reading_history urh 
    JOIN news n ON urh.news_id = n.id 
    WHERE urh.user_id = ? 
    ORDER BY urh.read_at DESC 
    LIMIT 10
");
$history_stmt->execute([$user_id]);
$reading_history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="profile-page py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4 mb-4">
                <div class="profile-sidebar">
                    <div class="profile-card text-center">
                        <div class="profile-avatar mb-3">
                            <?php if (!empty($user['profile_image'])): ?>
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($user['profile_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($user['username']); ?>" 
                                     class="rounded-circle" width="120" height="120">
                            <?php else: ?>
                                <div class="avatar-placeholder rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto text-white" 
                                     style="width: 120px; height: 120px; font-size: 3rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                        <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></p>
                        
                        <div class="profile-stats">
                            <div class="stat-item">
                                <strong><?php echo $favorites_count; ?></strong>
                                <span>Favorite</span>
                            </div>
                            <div class="stat-item">
                                <strong><?php echo $bookmarks_count; ?></strong>
                                <span>Bookmark</span>
                            </div>
                            <div class="stat-item">
                                <strong><?php echo count($reading_history); ?></strong>
                                <span>Lexuar</span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-menu">
                        <a href="favorites.php" class="profile-menu-item">
                            <i class="fas fa-heart me-2"></i>Lajmet e Preferuara
                            <span class="badge bg-primary float-end"><?php echo $favorites_count; ?></span>
                        </a>
                        <a href="bookmarks.php" class="profile-menu-item">
                            <i class="fas fa-bookmark me-2"></i>Programet e Bookmarked
                            <span class="badge bg-primary float-end"><?php echo $bookmarks_count; ?></span>
                        </a>
                        <a href="logout.php" class="profile-menu-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Dil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="profile-content">
                    <!-- Welcome Message -->
                    <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Mirë se erdhët! Llogaria juaj u krijua me sukses.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Profili Info -->
                    <div class="profile-section">
                        <h3 class="section-title">Informacioni i Profilit</h3>
                        <div class="profile-info">
                            <div class="info-item">
                                <label>Username:</label>
                                <span><?php echo htmlspecialchars($user['username']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Email:</label>
                                <span><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Emri i Plotë:</label>
                                <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Anëtar që:</label>
                                <span><?php echo formatDate($user['created_at']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Historiku i Leximit -->
                    <div class="profile-section">
                        <h3 class="section-title">Historiku i Leximit</h3>
                        <?php if (!empty($reading_history)): ?>
                            <div class="reading-history">
                                <?php foreach($reading_history as $news): ?>
                                <div class="history-item">
                                    <div class="history-content">
                                        <h5><?php echo htmlspecialchars($news['title']); ?></h5>
                                        <p class="text-muted small">
                                            Lexuar më: <?php echo formatDate($news['read_at']); ?>
                                        </p>
                                    </div>
                                    <a href="news-detail.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-history fa-2x mb-3"></i>
                                <p>Asnjë artikull i lexuar ende.</p>
                                <a href="news.php" class="btn btn-primary">Shiko Lajmet</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>