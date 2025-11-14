<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdminAuth();

$page_title = "Menaxho Programet TV";
include '../includes/header.php';

// Merr të gjitha programet TV
$stmt = $pdo->query("SELECT * FROM tv_shows ORDER BY show_day, show_time");
$tv_shows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_news.php"><i class="fas fa-newspaper"></i> Menaxho Lajmet</a></li>
            <li><a href="add_news.php"><i class="fas fa-plus"></i> Shto Lajm</a></li>
            <li><a href="manage_sports.php"><i class="fas fa-football-ball"></i> Sporti</a></li>
            <li><a href="manage_shows.php" class="active"><i class="fas fa-tv"></i> Programet TV</a></li>
            <li><a href="add_shows.php"><i class="fas fa-plus"></i> Shto Program</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Dil</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>Menaxho Programet TV</h1>
            <a href="add_shows.php" class="btn btn-primary">Shto Program të Ri</a>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
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
                                    S<?php echo $show['season']; ?>E<?php echo $show['episode']; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="edit_shows.php?id=<?php echo $show['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_shows.php?id=<?php echo $show['id']; ?>" class="btn btn-delete" onclick="return confirm('Jeni i sigurt?')">Fshi</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">Asnjë program TV</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>