<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Shto Lajm të Ri";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $content = $_POST['content'];
    $category = $_POST['category'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Handle file upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../' . UPLOAD_PATH;
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = UPLOAD_PATH . $image_name;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO news (title, description, content, image_path, category, featured) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $description, $content, $image_path, $category, $featured])) {
        $_SESSION['success'] = "Lajmi u shtua me sukses!";
        redirect('manage_news.php');
    } else {
        $error = "Gabim gjatë shtimit të lajmit!";
    }
}
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_news.php"><i class="fas fa-newspaper"></i> Menaxho Lajmet</a></li>
            <li><a href="add_news.php" class="active"><i class="fas fa-plus"></i> Shto Lajm</a></li>
            <li><a href="manage_sports.php"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <h1>Shto Lajm të Ri</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="news-form">
            <div class="form-group">
                <label for="title">Titulli:</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Përshkrimi:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="content">Përmbajtja:</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="category">Kategoria:</label>
                <select id="category" name="category" required>
                    <option value="news">Lajme</option>
                    <option value="sports">Sport</option>
                    <option value="shows">Programe TV</option>
                    <option value="national">Kombëtare</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="image">Foto:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <div class="form-group checkbox">
                <input type="checkbox" id="featured" name="featured">
                <label for="featured">Shëno si Kryesore</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Shto Lajmin</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>