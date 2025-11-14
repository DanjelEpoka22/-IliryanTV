<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$news_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    header('Location: index.php');
    exit();
}

$page_title = htmlspecialchars($news['title']);
include 'includes/header.php';
?>

<section class="news-detail">
    <div class="container">
        <article class="news-article">
            <h1><?php echo htmlspecialchars($news['title']); ?></h1>
            
            <div class="news-meta">
                <span class="news-date">
                    <i class="fas fa-calendar me-2"></i>
                    <?php echo isset($news['created_at']) ? formatDate($news['created_at']) : 'N/A'; ?>
                </span>
                <span class="news-category"><?php echo ucfirst(htmlspecialchars($news['category'] ?? 'Të Tjera')); ?></span>
            </div>
            
            <?php if(!empty($news['image_path']) && file_exists($news['image_path'])): ?>
            <div class="news-image-full">
                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($news['image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($news['title']); ?>">
            </div>
            <?php endif; ?>
            
            <div class="news-description">
                <p><strong><?php echo htmlspecialchars($news['description']); ?></strong></p>
            </div>
            
            <div class="news-content">
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>
            
            <div class="news-actions">
                <a href="news.php" class="btn btn-danger">
                    <i class="fas fa-arrow-left me-2"></i>Kthehu te Lajmet
                </a>
            </div>
        </article>
    </div>
</section>

<?php include 'includes/footer.php'; ?>