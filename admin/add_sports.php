<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Shto Event Sportiv";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $team_a = sanitize($_POST['team_a']);
    $team_b = sanitize($_POST['team_b']);
    $score_a = isset($_POST['score_a']) ? intval($_POST['score_a']) : NULL;
    $score_b = isset($_POST['score_b']) ? intval($_POST['score_b']) : NULL;
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("INSERT INTO sports_events (title, description, event_date, team_a, team_b, score_a, score_b, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$title, $description, $event_date, $team_a, $team_b, $score_a, $score_b, $status])) {
        $_SESSION['success'] = "Eventi sportiv u shtua me sukses!";
        redirect('manage_sports.php');
    } else {
        $error = "Gabim gjatë shtimit të eventit!";
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
            <li><a href="manage_sports.php"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="add_sports.php" class="active"><i class="fas fa-plus"></i> Shto Event</a></li>
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <h1>Shto Event Sportiv</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="sports-form">
            <div class="form-group">
                <label for="title">Titulli i Eventit *</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Përshkrimi</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="team_a">Ekipi A *</label>
                    <input type="text" id="team_a" name="team_a" required>
                </div>
                
                <div class="form-group">
                    <label for="team_b">Ekipi B *</label>
                    <input type="text" id="team_b" name="team_b" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="score_a">Rezultati A</label>
                    <input type="number" id="score_a" name="score_a" min="0">
                </div>
                
                <div class="form-group">
                    <label for="score_b">Rezultati B</label>
                    <input type="number" id="score_b" name="score_b" min="0">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="event_date">Data dhe Koha *</label>
                    <input type="datetime-local" id="event_date" name="event_date" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Statusi *</label>
                    <select id="status" name="status" required>
                        <option value="upcoming">Në vijim</option>
                        <option value="live">Live</option>
                        <option value="finished">Përfunduar</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Shto Eventin</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>