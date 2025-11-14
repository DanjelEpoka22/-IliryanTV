<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Kontrollo nëse ka ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: manage_news.php');
    exit();
}

$news_id = intval($_GET['id']);

// Merr të dhënat e lajmit
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    header('Location: manage_news.php');
    exit();
}

$page_title = "Edit Lajm";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $content = $_POST['content'];
    $category = $_POST['category'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Handle file upload
    $image_path = $news['image_path']; // Mbaj foto-n ekzistuese
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../' . UPLOAD_PATH;
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Fshi foto-n e vjetër nëse ekziston
            if ($news['image_path'] && file_exists('../' . $news['image_path'])) {
                unlink('../' . $news['image_path']);
            }
            $image_path = UPLOAD_PATH . $image_name;
        }
    }
    
    // Handle delete image request
    if (isset($_POST['delete_image']) && $_POST['delete_image'] == '1') {
        if ($news['image_path'] && file_exists('../' . $news['image_path'])) {
            unlink('../' . $news['image_path']);
        }
        $image_path = null;
    }
    
    $stmt = $pdo->prepare("UPDATE news SET title = ?, description = ?, content = ?, image_path = ?, category = ?, featured = ?, updated_at = NOW() WHERE id = ?");
    
    if ($stmt->execute([$title, $description, $content, $image_path, $category, $featured, $news_id])) {
        $_SESSION['success'] = "Lajmi u përditësua me sukses!";
        header('Location: manage_news.php');
        exit();
    } else {
        $error = "Gabim gjatë përditësimit të lajmit!";
    }
}
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_news.php"><i class="fas fa-newspaper"></i> Menaxho Lajmet</a></li>
            <li><a href="add_news.php"><i class="fas fa-plus"></i> Shto Lajm</a></li>
            <li><a href="edit_news.php?id=<?php echo $news_id; ?>" class="active"><i class="fas fa-edit"></i> Edit Lajm</a></li>
            <li><a href="manage_sports.php"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <h1>Edit Lajm</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="news-form">
            <div class="form-group">
                <label for="title">Titulli:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Përshkrimi:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($news['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="content">Përmbajtja:</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="category">Kategoria:</label>
                <select id="category" name="category" required>
                    <option value="news" <?php echo $news['category'] == 'news' ? 'selected' : ''; ?>>Lajme</option>
                    <option value="sports" <?php echo $news['category'] == 'sports' ? 'selected' : ''; ?>>Sport</option>
                    <option value="shows" <?php echo $news['category'] == 'shows' ? 'selected' : ''; ?>>Programe TV</option>
                    <option value="national" <?php echo $news['category'] == 'national' ? 'selected' : ''; ?>>Kombëtare</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="image">Foto:</label>
                <input type="file" id="image" name="image" accept="image/*">
                
                <?php if($news['image_path']): ?>
                <div class="current-image">
                    <p>Foto aktuale:</p>
                    <img src="<?php echo SITE_URL . '/' . $news['image_path']; ?>" alt="Current Image" class="image-preview">
                    <div class="form-group checkbox">
                        <input type="checkbox" id="delete_image" name="delete_image" value="1">
                        <label for="delete_image">Fshi foton aktuale</label>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group checkbox">
                <input type="checkbox" id="featured" name="featured" value="1" <?php echo $news['featured'] ? 'checked' : ''; ?>>
                <label for="featured">Shëno si Kryesore</label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Përditëso Lajmin</button>
                <a href="manage_news.php" class="btn btn-secondary">Anulo</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>