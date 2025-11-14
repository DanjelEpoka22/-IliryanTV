<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Kryefaqja - IliryanTV";
include 'includes/header.php';

// Get featured and latest news
$featured_news = getFeaturedNews($pdo, 5);
$latest_news = getLatestNews($pdo, 8);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                Iliryan<span class="text-danger">TV</span>
            </h1>
            <p class="hero-subtitle">
                Televizioni patriotik shqiptar - Lajmet më të fundit në kohë reale
            </p>
            <div class="hero-buttons">
                <a href="#latest-news" class="btn btn-danger btn-lg">
                    <i class="fas fa-newspaper me-2"></i>Eksploro Lajmet
                </a>
                <a href="news.php" class="btn btn-outline-danger btn-lg">
                    <i class="fas fa-play-circle me-2"></i>Shiko Live
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section id="latest-news" class="py-5">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Lajmet e Fundit</h2>
            <p class="section-subtitle">Mbani veten e informuar me lajmet më të freskëta</p>
        </div>
        
        <div class="news-grid-full">
            <?php if (!empty($latest_news)): ?>
                <?php foreach($latest_news as $news): ?>
                <div class="news-card">
                    <div class="news-image">
                        <?php if(!empty($news['image_path']) && file_exists($news['image_path'])): ?>
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($news['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                 loading="lazy">
                        <?php else: ?>
                            <div class="news-image-placeholder">
                                <i class="fas fa-newspaper"></i>
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
                            <span class="news-category"><?php echo ucfirst(htmlspecialchars($news['category'] ?? 'Të Tjera')); ?></span>
                        </div>
                        <a href="news-detail.php?id=<?php echo $news['id']; ?>" class="read-more">
                            Lexo më shumë <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Asnjë lajm i disponueshëm në këtë kohë.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="news.php" class="btn btn-danger btn-lg">
                <i class="fas fa-list me-2"></i>Shiko të Gjitha Lajmet
            </a>
        </div>
    </div>
</section>

<!-- Quick Links Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-football-ball fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Sporti</h5>
                        <p class="card-text">Ndiqni eventet sportive</p>
                        <a href="sports.php" class="btn btn-outline-danger">Shiko Sportin</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-tv fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Programet TV</h5>
                        <p class="card-text">Orari i programeve</p>
                        <a href="shows.php" class="btn btn-outline-danger">Shiko Programet</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Kontakt</h5>
                        <p class="card-text">Na kontaktoni</p>
                        <a href="contact.php" class="btn btn-outline-danger">Kontakt</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>