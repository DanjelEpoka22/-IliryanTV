<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Lajmet";
include 'includes/header.php';

try {
    // Get all news with pagination
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $per_page = 12;
    $offset = ($page - 1) * $per_page;

    // Validate page number
    if ($page < 1) {
        $page = 1;
        $offset = 0;
    }

    // Get news data
    $stmt = $pdo->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindParam(1, $per_page, PDO::PARAM_INT);
    $stmt->bindParam(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $all_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count
    $count_stmt = $pdo->query("SELECT COUNT(*) as total FROM news");
    $total_row = $count_stmt->fetch(PDO::FETCH_ASSOC);
    $total = $total_row['total'] ?? 0;
    $total_pages = $total > 0 ? ceil($total / $per_page) : 1;

    // Ensure page doesn't exceed total pages
    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
    }

} catch (Exception $e) {
    $all_news = [];
    $total_pages = 1;
    $page = 1;
    $error_message = "Gabim në ngarkim të lajmeve: " . $e->getMessage();
}
?>

<section class="news-page py-5">
    <div class="container">
        <h1 class="page-title">Të gjitha Lajmet</h1>
        
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="news-grid-full">
            <?php if (!empty($all_news)): ?>
                <?php foreach($all_news as $news): ?>
                <div class="news-card">
                    <div class="news-image">
                        <?php if (!empty($news['image_path']) && file_exists($news['image_path'])): ?>
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
                        <p><?php echo htmlspecialchars(substr($news['description'], 0, 150)); ?></p>
                        <div class="news-meta">
                            <span class="news-date">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo isset($news['created_at']) ? formatDate($news['created_at']) : 'N/A'; ?>
                            </span>
                            <span class="news-category"><?php echo ucfirst(htmlspecialchars($news['category'] ?? 'Të Tjera')); ?></span>
                        </div>
                        <a href="news-detail.php?id=<?php echo intval($news['id']); ?>" class="read-more">
                            Lexo më shumë <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted fs-5">Asnjë lajm i disponueshëm në këtë kohë.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="news.php?page=1" title="Faqja e parë">
                        <i class="fas fa-step-backward me-1"></i><span class="d-none d-sm-inline">Fillim</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="news.php?page=<?php echo $page - 1; ?>" title="Faqja e mëparshme">
                        <i class="fas fa-chevron-left me-1"></i><span class="d-none d-sm-inline">Mëparshme</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php 
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);
                
                if ($start > 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif;

                for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="news.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor;

                if ($end < $total_pages): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="news.php?page=<?php echo $page + 1; ?>" title="Faqja e ardhshme">
                        <span class="d-none d-sm-inline">Tjetra</span><i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="news.php?page=<?php echo $total_pages; ?>" title="Faqja e fundit">
                        <span class="d-none d-sm-inline">Fund</span><i class="fas fa-step-forward ms-1"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>