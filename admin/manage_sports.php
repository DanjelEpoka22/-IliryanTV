<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Merr të gjitha eventet sportive
$stmt = $pdo->query("SELECT * FROM sports_events ORDER BY event_date DESC");
$sports_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Menaxho Eventet Sportive";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menaxho Eventet Sportive</h1>
    <a href="add_sports.php" class="btn btn-primary">Shto Event të Ri</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
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
                                <?php 
                                $status_classes = [
                                    'upcoming' => 'bg-warning',
                                    'live' => 'bg-danger',
                                    'finished' => 'bg-success'
                                ];
                                $status_labels = [
                                    'upcoming' => 'Në vijim',
                                    'live' => 'Live',
                                    'finished' => 'Përfunduar'
                                ];
                                ?>
                                <span class="badge <?php echo $status_classes[$event['status']]; ?>">
                                    <?php echo $status_labels[$event['status']]; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_sports.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete_sports.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-football-ball fa-2x mb-3"></i>
                                <p>Asnjë event sportiv</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>