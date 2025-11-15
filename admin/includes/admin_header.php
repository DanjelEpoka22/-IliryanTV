<?php
// Fillo output buffering për të shmangur header errors
if (!ob_get_level()) {
    ob_start();
}

// Për faqet e adminit, përdor session të njëjtë por pa konflikte
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kontrollo nëse admini është i loguar
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Pastro buffer dhe redirect
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Location: login.php');
    exit();
}

// MOS SHKRUAJ ASGJË KËTU PARA TAG-UT <!DOCTYPE>
// Asnjë hapësirë, asnjë karakter, asgjë!
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background: #2c3e50;
            min-height: 100vh;
            padding: 0;
        }
        .admin-sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: #34495e;
            color: #3498db;
        }
        .admin-sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .admin-content {
            background: #ecf0f1;
            min-height: 100vh;
            padding: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #3498db;
        }
        .stat-info h3 {
            font-size: 2.5rem;
            margin: 0;
            color: #2c3e50;
        }
        .stat-info p {
            color: #7f8c8d;
            margin: 5px 0 0 0;
        }
        .sidebar-heading {
            border-bottom: 1px solid #34495e;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Admin Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar admin-sidebar">
                <div class="position-sticky pt-3">
                    <div class="sidebar-heading px-3 py-4">
                        <h4 class="text-white">
                            <i class="fas fa-crown me-2 text-warning"></i>Admin Panel
                        </h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_news.php', 'add_news.php', 'edit_news.php']) ? 'active' : ''; ?>" href="manage_news.php">
                                <i class="fas fa-newspaper"></i> Menaxho Lajmet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_sports.php', 'add_sports.php']) ? 'active' : ''; ?>" href="manage_sports.php">
                                <i class="fas fa-football-ball"></i> Sporti
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_shows.php', 'add_shows.php']) ? 'active' : ''; ?>" href="manage_shows.php">
                                <i class="fas fa-tv"></i> Programet TV
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link text-warning" href="../index.php" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Shiko Faqen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Dil
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content">