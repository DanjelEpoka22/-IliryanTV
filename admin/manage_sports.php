<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Menaxho Eventet Sportive";
include '../includes/header.php';

// Merr të gjitha eventet sportive
$stmt = $pdo->query("SELECT * FROM sports_events ORDER BY event_date DESC");
$sports_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_news.php"><i class="fas fa-newspaper"></i> Menaxho Lajmet</a></li>
            <li><a href="add_news.php"><i class="fas fa-plus"></i> Shto Lajm</a></li>
            <li><a href="manage_sports.php" class="active"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="add_sports.php"><i class="fas fa-plus"></i> Shto Event</a></li>
            <li><a href="manage_shows.php"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>Menaxho Eventet Sportive</h1>
            <a href="add_sports.php" class="btn btn-primary">Shto Event të Ri</a>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titulli</th>
                        <th>Ekipet</th>
                        <th>Data</th>
                        <th>Statusi</th>
                        <th>Veprimet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sports_events)): ?>
                        <?php foreach($sports_events as $event): ?>
                        <tr>
                            <td><?php echo $event['title']; ?></td>
                            <td><?php echo $event['team_a'] . ' vs ' . $event['team_b']; ?></td>
                            <td><?php echo formatDate($event['event_date']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $event['status']; ?>">
                                    <?php 
                                    $status_labels = [
                                        'upcoming' => 'Në vijim',
                                        'live' => 'Live',
                                        'finished' => 'Përfunduar'
                                    ];
                                    echo $status_labels[$event['status']];
                                    ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="edit_sports.php?id=<?php echo $event['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_sports.php?id=<?php echo $event['id']; ?>" class="btn btn-delete" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">Asnjë event sportiv</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>