<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Variablat për formën
$username = $email = $first_name = $last_name = '';
$errors = [];

// Përpunimi i formës - BËHU PARA SE TË PËRFSHIJ HEADER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');

    // Validimi
    if (empty($username)) {
        $errors['username'] = 'Username është i detyrueshëm';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username duhet të jetë së paku 3 karaktere';
    }

    if (empty($email)) {
        $errors['email'] = 'Email është i detyrueshëm';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email jo valid';
    }

    if (empty($password)) {
        $errors['password'] = 'Password është i detyrueshëm';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password duhet të jetë së paku 6 karaktere';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Password-et nuk përputhen';
    }

    // Kontrollo nëse username ose email ekzistojnë
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $errors['general'] = 'Username ose email ekzistojnë tashmë';
            }
        } catch (PDOException $e) {
            $errors['general'] = 'Gabim në server: ' . $e->getMessage();
        }
    }

    // Regjistro userin
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name]);
            
            // Auto-login pas regjistrimit
            $user_id = $pdo->lastInsertId();
            
            // Start session për login
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            require_once 'includes/user_auth.php';
            userLogin($user_id, $username);
            
            // REDIRECT PARA SE TË DËRGOJË OUTPUT
            header('Location: profile.php?registered=1');
            exit();
            
        } catch (PDOException $e) {
            $errors['general'] = 'Gabim në regjistrim: ' . $e->getMessage();
        }
    }
}

// TANI MUND TË PËRFSHIJ HEADER
$page_title = "Regjistrohu";
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
                        <h1 class="page-title">Regjistrohu</h1>
                        <p class="text-muted">Krijo llogari të re për të përfituar nga të gjitha veçoritë</p>
                    </div>

                    <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($errors['general']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Emri</label>
                                    <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                           id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                                    <?php if (isset($errors['first_name'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['first_name']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Mbiemri</label>
                                    <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                           id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                                    <?php if (isset($errors['last_name'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['last_name']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                   id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['username']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   id="password" name="password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['password']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Konfirmo Password *</label>
                            <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                   id="confirm_password" name="confirm_password" required>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['confirm_password']); ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Regjistrohu
                        </button>

                        <div class="text-center">
                            <p class="mb-0">Keni llogari? <a href="login.php" class="text-decoration-none">Hyni këtu</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>