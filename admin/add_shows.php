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
    $show_time = $_POST['show_time'];
    $show_day = $_POST['show_day'];
    $season = isset($_POST['season']) ? intval($_POST['season']) : NULL;
    $episode = isset($_POST['episode']) ? intval($_POST['episode']) : NULL;
    
    $stmt = $pdo->prepare("INSERT INTO tv_shows (title, description, show_time, show_day, season, episode) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$title, $description, $show_time, $show_day, $season, $episode])) {
        $_SESSION['success'] = "Programi TV u shtua me sukses!";
        // REDIRECT PARA SE TË DËRGOJË OUTPUT
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: manage_shows.php');
        exit();
    } else {
        $error = "Gabim gjatë shtimit të programit!";
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Shto Program TV";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Shto Program TV</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Titulli i Programit *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="show_day" class="form-label">Dita e Java *</label>
                    <select class="form-control" id="show_day" name="show_day" required>
                        <option value="Monday">E Hënë</option>
                        <option value="Tuesday">E Martë</option>
                        <option value="Wednesday">E Mërkurë</option>
                        <option value="Thursday">E Enjte</option>
                        <option value="Friday">E Premte</option>
                        <option value="Saturday">E Shtunë</option>
                        <option value="Sunday">E Dielë</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="show_time" class="form-label">Koha *</label>
                    <input type="time" class="form-control" id="show_time" name="show_time" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Përshkrimi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="season" class="form-label">Sezoni</label>
                    <input type="number" class="form-control" id="season" name="season" min="1">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="episode" class="form-label">Episodi</label>
                    <input type="number" class="form-control" id="episode" name="episode" min="1">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Shto Programin</button>
            <a href="manage_shows.php" class="btn btn-secondary">Anulo</a>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>