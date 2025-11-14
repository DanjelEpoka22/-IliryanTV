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
</head>
<body>
    <!-- Loading Spinner (hidden by default) -->
    <div id="loading-spinner" class="loading-spinner" style="display: none;">
        <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">IliryanTV</p>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="<?php echo SITE_URL; ?>/index.php">
                <i class="fas fa-tv me-2 text-danger"></i>
                <span class="text-gradient">Iliryan</span><span class="text-danger">TV</span>
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