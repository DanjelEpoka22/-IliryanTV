<?php
// Fillo output buffering
if (!ob_get_level()) {
    ob_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

// Merr të gjitha programet TV
$stmt = $pdo->query("SELECT * FROM tv_shows ORDER BY show_day, show_time");
$tv_shows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Menaxho Programet TV";
include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menaxho Programet TV</h1>
    <a href="add_shows.php" class="btn btn-primary">Shto Program të Ri</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Titulli</th>
                        <th>Dita</th>
                        <th>Koha</th>
                        <th>Sezoni/Episodi</th>
                        <th>Veprimet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tv_shows)): ?>
                        <?php foreach($tv_shows as $show): ?>
                        <tr>
                            <td><?php echo $show['title']; ?></td>
                            <td>
                                <?php 
                                $day_names = [
                                    'Monday' => 'E Hënë',
                                    'Tuesday' => 'E Martë',
                                    'Wednesday' => 'E Mërkurë',
                                    'Thursday' => 'E Enjte',
                                    'Friday' => 'E Premte',
                                    'Saturday' => 'E Shtunë',
                                    'Sunday' => 'E Dielë'
                                ];
                                echo $day_names[$show['show_day']];
                                ?>
                            </td>
                            <td><?php echo date('H:i', strtotime($show['show_time'])); ?></td>
                            <td>
                                <?php if ($show['season'] && $show['episode']): ?>
                                    <span class="badge bg-primary">S<?php echo $show['season']; ?>E<?php echo $show['episode']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_shows.php?id=<?php echo $show['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete_shows.php?id=<?php echo $show['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-tv fa-2x mb-3"></i>
                                <p>Asnjë program TV</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>