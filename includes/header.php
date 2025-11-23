<?php
// Start session nëse nuk është startuar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Përfshij user authentication functions
require_once __DIR__ . '/user_auth.php';
?>
<!DOCTYPE html>
<html lang="sq" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Glightbox -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css" rel="stylesheet">
    
    <!-- Inline critical CSS to hide spinner immediately -->
    <style>
        #loading-spinner {
            display: none !important;
        }
    </style>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/user.css">
</head>
<body>
    <!-- Loading Spinner (hidden by default) -->
    <div id="loading-spinner" class="loading-spinner" style="display: none;">
        <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">IllyrianTV</p>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="<?php echo SITE_URL; ?>/index.php">
                <i class="fas fa-tv me-2 text-danger"></i>
                <span class="text-gradient">Illyrian</span><span class="text-danger">TV</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/index.php">
                            <i class="fas fa-home me-1"></i>Kryefaqja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/news.php">
                            <i class="fas fa-newspaper me-1"></i>Lajmet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'sports.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/sports.php">
                            <i class="fas fa-football-ball me-1"></i>Sporti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shows.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/shows.php">
                            <i class="fas fa-tv me-1"></i>Programet TV
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/contact.php">
                            <i class="fas fa-envelope me-1"></i>Kontakt
                        </a>
                    </li>
                    
                    <!-- ✅ USER MENU -->
                    <?php if (isUserLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-success" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars(getUserUsername()); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profile.php">
                                <i class="fas fa-user-circle me-2"></i>Profili
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/favorites.php">
                                <i class="fas fa-heart me-2"></i>Lajmet e Preferuara
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/bookmarks.php">
                                <i class="fas fa-bookmark me-2"></i>Programet e Bookmarked
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Dil
                            </a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?php echo SITE_URL; ?>/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Hyr
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="<?php echo SITE_URL; ?>/register.php">
                            <i class="fas fa-user-plus me-1"></i>Regjistrohu
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (function_exists('isAdminLoggedIn') && isAdminLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?php echo SITE_URL; ?>/admin/dashboard.php">
                            <i class="fas fa-crown me-1"></i>Admin
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="pt-5">