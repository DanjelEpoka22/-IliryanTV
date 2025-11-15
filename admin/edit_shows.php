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
    header('Location: manage_shows.php');
    exit();
}

$show_id = intval($_GET['id']);

// Merr të dhënat e programit TV
$stmt = $pdo->prepare("SELECT * FROM tv_shows WHERE id = ?");
$stmt->execute([$show_id]);
$show = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$show) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: manage_shows.php');
    exit();
}

// PËRPUNIMI I FORMËS DUHET TË JETË PARA HEADER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $show_time = $_POST['show_time'];
    $show_day = $_POST['show_day'];
    $season = isset($_POST['season']) ? intval($_POST['season']) : NULL;
    $episode = isset($_POST['episode']) ? intval($_POST['episode']) : NULL;
    
    $stmt = $pdo->prepare("UPDATE tv_shows SET title = ?, description = ?, show_time = ?, show_day = ?, season = ?, episode = ? WHERE id = ?");
    
    if ($stmt->execute([$title, $description, $show_time, $show_day, $season, $episode, $show_id])) {
        $_SESSION['success'] = "Programi TV u përditësua me sukses!";
        // REDIRECT PARA SE TË DËRGOJË OUTPUT
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: manage_shows.php');
        exit();
    } else {
        $error = "Gabim gjatë përditësimit të programit!";
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Edit Program TV";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Program TV</h1>
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
                    <input type="text" class="form-control" id="title" name="title" 
                           value="<?php echo htmlspecialchars($show['title']); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="show_day" class="form-label">Dita e Java *</label>
                    <select class="form-control" id="show_day" name="show_day" required>
                        <option value="Monday" <?php echo $show['show_day'] == 'Monday' ? 'selected' : ''; ?>>E Hënë</option>
                        <option value="Tuesday" <?php echo $show['show_day'] == 'Tuesday' ? 'selected' : ''; ?>>E Martë</option>
                        <option value="Wednesday" <?php echo $show['show_day'] == 'Wednesday' ? 'selected' : ''; ?>>E Mërkurë</option>
                        <option value="Thursday" <?php echo $show['show_day'] == 'Thursday' ? 'selected' : ''; ?>>E Enjte</option>
                        <option value="Friday" <?php echo $show['show_day'] == 'Friday' ? 'selected' : ''; ?>>E Premte</option>
                        <option value="Saturday" <?php echo $show['show_day'] == 'Saturday' ? 'selected' : ''; ?>>E Shtunë</option>
                        <option value="Sunday" <?php echo $show['show_day'] == 'Sunday' ? 'selected' : ''; ?>>E Dielë</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="show_time" class="form-label">Koha *</label>
                    <input type="time" class="form-control" id="show_time" name="show_time" 
                           value="<?php echo date('H:i', strtotime($show['show_time'])); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Përshkrimi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($show['description'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="season" class="form-label">Sezoni</label>
                    <input type="number" class="form-control" id="season" name="season" 
                           value="<?php echo $show['season'] ?? ''; ?>" min="1">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="episode" class="form-label">Episodi</label>
                    <input type="number" class="form-control" id="episode" name="episode" 
                           value="<?php echo $show['episode'] ?? ''; ?>" min="1">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Përditëso Programin</button>
            <a href="manage_shows.php" class="btn btn-secondary">Anulo</a>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>