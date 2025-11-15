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
    header('Location: manage_sports.php');
    exit();
}

$sport_id = intval($_GET['id']);

// Merr të dhënat e eventit sportiv
$stmt = $pdo->prepare("SELECT * FROM sports_events WHERE id = ?");
$stmt->execute([$sport_id]);
$sport = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sport) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: manage_sports.php');
    exit();
}

// PËRPUNIMI I FORMËS DUHET TË JETË PARA HEADER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $team_a = sanitize($_POST['team_a']);
    $team_b = sanitize($_POST['team_b']);
    $score_a = isset($_POST['score_a']) ? intval($_POST['score_a']) : NULL;
    $score_b = isset($_POST['score_b']) ? intval($_POST['score_b']) : NULL;
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE sports_events SET title = ?, description = ?, event_date = ?, team_a = ?, team_b = ?, score_a = ?, score_b = ?, status = ? WHERE id = ?");
    
    if ($stmt->execute([$title, $description, $event_date, $team_a, $team_b, $score_a, $score_b, $status, $sport_id])) {
        $_SESSION['success'] = "Eventi sportiv u përditësua me sukses!";
        // REDIRECT PARA SE TË DËRGOJË OUTPUT
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Location: manage_sports.php');
        exit();
    } else {
        $error = "Gabim gjatë përditësimit të eventit!";
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Edit Event Sportiv";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Event Sportiv</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titulli i Eventit *</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?php echo htmlspecialchars($sport['title']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Përshkrimi</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($sport['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="team_a" class="form-label">Ekipi A *</label>
                    <input type="text" class="form-control" id="team_a" name="team_a" 
                           value="<?php echo htmlspecialchars($sport['team_a']); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="team_b" class="form-label">Ekipi B *</label>
                    <input type="text" class="form-control" id="team_b" name="team_b" 
                           value="<?php echo htmlspecialchars($sport['team_b']); ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="score_a" class="form-label">Rezultati A</label>
                    <input type="number" class="form-control" id="score_a" name="score_a" 
                           value="<?php echo $sport['score_a'] ?? ''; ?>" min="0">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="score_b" class="form-label">Rezultati B</label>
                    <input type="number" class="form-control" id="score_b" name="score_b" 
                           value="<?php echo $sport['score_b'] ?? ''; ?>" min="0">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_date" class="form-label">Data dhe Koha *</label>
                    <input type="datetime-local" class="form-control" id="event_date" name="event_date" 
                           value="<?php echo date('Y-m-d\TH:i', strtotime($sport['event_date'])); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Statusi *</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="upcoming" <?php echo $sport['status'] == 'upcoming' ? 'selected' : ''; ?>>Në vijim</option>
                        <option value="live" <?php echo $sport['status'] == 'live' ? 'selected' : ''; ?>>Live</option>
                        <option value="finished" <?php echo $sport['status'] == 'finished' ? 'selected' : ''; ?>>Përfunduar</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Përditëso Eventin</button>
            <a href="manage_sports.php" class="btn btn-secondary">Anulo</a>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>