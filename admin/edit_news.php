<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Kontrollo nëse ka ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: manage_news.php');
    exit();
}

$news_id = intval($_GET['id']);

// Merr të dhënat e lajmit
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: manage_news.php');
    exit();
}

// PËRPUNIMI I FORMËS DUHET TË JETË PARA HEADER
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
        // REDIRECT PARA SE TË DËRGOJË OUTPUT
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: manage_news.php');
        exit();
    } else {
        $error = "Gabim gjatë përditësimit të lajmit!";
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Edit Lajm";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Lajm</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titulli:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Përshkrimi:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($news['description']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Përmbajtja:</label>
                <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Kategoria:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="news" <?php echo $news['category'] == 'news' ? 'selected' : ''; ?>>Lajme</option>
                    <option value="sports" <?php echo $news['category'] == 'sports' ? 'selected' : ''; ?>>Sport</option>
                    <option value="shows" <?php echo $news['category'] == 'shows' ? 'selected' : ''; ?>>Programe TV</option>
                    <option value="national" <?php echo $news['category'] == 'national' ? 'selected' : ''; ?>>Kombëtare</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Foto:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                
                <?php if($news['image_path']): ?>
                <div class="mt-2">
                    <p>Foto aktuale:</p>
                    <img src="<?php echo SITE_URL . '/' . $news['image_path']; ?>" alt="Current Image" style="max-width: 200px; height: auto;" class="img-thumbnail">
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="delete_image" name="delete_image" value="1">
                        <label class="form-check-label" for="delete_image">Fshi foton aktuale</label>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1" <?php echo $news['featured'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="featured">Shëno si Kryesore</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Përditëso Lajmin</button>
            <a href="manage_news.php" class="btn btn-secondary">Anulo</a>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>