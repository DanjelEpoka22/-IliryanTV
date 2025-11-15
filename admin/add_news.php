<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// PËRPUNIMI I FORMËS DUHET TË JETË PARA HEADER
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
        // REDIRECT PARA SE TË DËRGOJË OUTPUT
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: manage_news.php');
        exit();
    } else {
        $error = "Gabim gjatë shtimit të lajmit!";
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Shto Lajm të Ri";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Shto Lajm të Ri</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titulli:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Përshkrimi:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Përmbajtja:</label>
                <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Kategoria:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="news">Lajme</option>
                    <option value="sports">Sport</option>
                    <option value="shows">Programe TV</option>
                    <option value="national">Kombëtare</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Foto:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="featured" name="featured">
                <label class="form-check-label" for="featured">Shëno si Kryesore</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Shto Lajmin</button>
            <a href="manage_news.php" class="btn btn-secondary">Anulo</a>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>