<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// If already logged in, redirect to dashboard
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Ju lutem plotësoni të dy fushat!";
    } else {
        // Use adminLogin helper (it handles session_regenerate_id)
        if (adminLogin($username, $password, $pdo)) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Kredencialet janë të pasakta!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <h1><i class="fas fa-tv"></i> IliryanTV</h1>
                <p>Admin Login</p>
            </div>

            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input autocomplete="off" type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input autocomplete="new-password" type="password" id="password" name="password" value="" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Hyr</button>
            </form>

        </div>
    </div>
</body>
</html>