<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Menaxho Lajmet";
include '../includes/header.php';

// Merr të gjitha lajmet
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$all_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kontrollo për mesazhin e suksesit
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
if (isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_news.php" class="active"><i class="fas fa-newspaper"></i> Menaxho Lajmet</a></li>
            <li><a href="add_news.php"><i class="fas fa-plus"></i> Shto Lajm</a></li>
            <li><a href="manage_sports.php"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>Menaxho Lajmet</h1>
            <a href="add_news.php" class="btn btn-primary">Shto Lajm të Ri</a>
        </div>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titulli</th>
                        <th>Kategoria</th>
                        <th>Data</th>
                        <th>Kryesore</th>
                        <th>Veprimet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all_news)): ?>
                        <?php foreach($all_news as $news): ?>
                        <tr>
                            <td>
                                <div class="news-title-cell">
                                    <?php if($news['image_path']): ?>
                                    <img src="<?php echo SITE_URL . '/' . $news['image_path']; ?>" alt="<?php echo $news['title']; ?>" class="news-thumb-admin">
                                    <?php endif; ?>
                                    <span><?php echo $news['title']; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge"><?php echo ucfirst($news['category']); ?></span>
                            </td>
                            <td><?php echo formatDate($news['created_at']); ?></td>
                            <td>
                                <?php if ($news['featured']): ?>
                                    <span class="featured-badge">Kryesore</span>
                                <?php else: ?>
                                    <span class="normal-badge">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="edit_news.php?id=<?php echo $news['id']; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_news.php?id=<?php echo $news['id']; ?>" class="btn btn-delete" onclick="return confirm('Jeni i sigurt që dëshironi të fshini këtë lajm?')">
                                    <i class="fas fa-trash"></i> Fshi
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">Asnjë lajm në database</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>