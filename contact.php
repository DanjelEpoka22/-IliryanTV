<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Kontakt";
include 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    // Validime të thjeshta
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Ju lutem plotësoni të gjitha fushat!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email i pavlefshëm!";
    } else {
        // Këtu mund të dërgohet email ose të ruhet në database
        // Për momentin, thjesht tregojmë mesazhin e suksesit
        $success = "Faleminderit për mesazhin tuaj! Do t'ju kontaktojmë së shpejti.";
        
        // Reset form
        $_POST = array();
    }
}
?>

<section class="contact-page">
    <div class="container">
        <h1 class="page-title">Na Kontaktoni</h1>
        
        <div class="contact-content">
            <div class="contact-info">
                <h2>Informacione Kontakti</h2>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Adresa</h3>
                        <p>Rruga Iliria, Nr. 123<br>Tiranë, Shqipëri</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Telefon</h3>
                        <p>+355 4 123 4567</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@iliryantv.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Orari i Punës</h3>
                        <p>E Hënë - E Premte: 08:00 - 20:00<br>
                           E Shtunë - E Dielë: 09:00 - 18:00</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h2>Dërgo Mesazh</h2>
                
                <?php if ($success): ?>
                    <div class="alert success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Emri i Plotë *</label>
                            <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subjekti *</label>
                        <input type="text" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Mesazhi *</label>
                        <textarea id="message" name="message" rows="6" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Dërgo Mesazhin</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>