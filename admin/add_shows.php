<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Shto Program TV";
include '../includes/header.php';

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
        redirect('manage_shows.php');
    } else {
        $error = "Gabim gjatë shtimit të programit!";
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
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="add_shows.php" class="active"><i class="fas fa-plus"></i> Shto Program</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <h1>Shto Program TV</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="shows-form">
            <div class="form-group">
                <label for="title">Titulli i Programit *</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Përshkrimi</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="show_day">Dita e Java *</label>
                    <select id="show_day" name="show_day" required>
                        <option value="Monday">E Hënë</option>
                        <option value="Tuesday">E Martë</option>
                        <option value="Wednesday">E Mërkurë</option>
                        <option value="Thursday">E Enjte</option>
                        <option value="Friday">E Premte</option>
                        <option value="Saturday">E Shtunë</option>
                        <option value="Sunday">E Dielë</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="show_time">Koha *</label>
                    <input type="time" id="show_time" name="show_time" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="season">Sezoni</label>
                    <input type="number" id="season" name="season" min="1">
                </div>
                
                <div class="form-group">
                    <label for="episode">Episodi</label>
                    <input type="number" id="episode" name="episode" min="1">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Shto Programin</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>