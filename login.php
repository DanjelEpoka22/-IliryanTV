<?php
// Fillo output buffering për të shmangur header errors
ob_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';

// Variablat për formën
$username = '';
$errors = [];

// Përpunimi i formës - BËHU PARA SE TË PËRFSHIJ HEADER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validimi
    if (empty($username)) {
        $errors['username'] = 'Username është i detyrueshëm';
    }

    if (empty($password)) {
        $errors['password'] = 'Password është i detyrueshëm';
    }

    // Verifikimi i kredencialeve
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Start session për login
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                require_once 'includes/user_auth.php';
                userLogin($user['id'], $user['username']);
                
                // REDIRECT PARA SE TË DËRGOJË OUTPUT
                if (isset($_GET['redirect'])) {
                    header('Location: ' . urldecode($_GET['redirect']));
                } else {
                    header('Location: profile.php');
                }
                exit();
            } else {
                $errors['general'] = 'Username/email ose password i gabuar';
            }
        } catch (PDOException $e) {
            $errors['general'] = 'Gabim në server: ' . $e->getMessage();
        }
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Hyr";
include 'includes/header.php';

// Nëse useri është i loguar, ridrejto në profil (duhet të jetë pas header)
if (isUserLoggedIn()) {
    header('Location: profile.php');
    exit();
}
?>

<section class="auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header text-center mb-4">
                        <h1 class="page-title">Hyr në Llogari</h1>
                        <p class="text-muted">Hyr për të aksesuar të gjitha veçoritë</p>
                    </div>

                    <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($errors['general']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username ose Email *</label>
                            <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                   id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['username']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   id="password" name="password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['password']); ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Hyr
                        </button>

                        <div class="text-center">
                            <p class="mb-0">Nuk keni llogari? <a href="register.php" class="text-decoration-none">Regjistrohu këtu</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
include 'includes/footer.php'; 
ob_end_flush();
?>