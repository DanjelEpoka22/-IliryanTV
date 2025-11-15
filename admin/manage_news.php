<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Merr të gjitha lajmet
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$all_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kontrollo për mesazhin e suksesit
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
if (isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Menaxho Lajmet";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menaxho Lajmet</h1>
    <a href="add_news.php" class="btn btn-primary">Shto Lajm të Ri</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Titulli</th>
                        <th>Kategoria</th>
                        <th>Data</th>
                        <th>Statusi</th>
                        <th>Veprimet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all_news)): ?>
                        <?php foreach($all_news as $news): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($news['image_path']): ?>
                                    <img src="<?php echo SITE_URL . '/' . $news['image_path']; ?>" alt="<?php echo $news['title']; ?>" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php endif; ?>
                                    <span><?php echo $news['title']; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo ucfirst($news['category']); ?></span>
                            </td>
                            <td><?php echo formatDate($news['created_at']); ?></td>
                            <td>
                                <?php if ($news['featured']): ?>
                                    <span class="badge bg-warning">Kryesore</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_news.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_news.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Jeni i sigurt që dëshironi të fshini këtë lajm?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <p>Asnjë lajm në database</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>