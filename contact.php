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

<!-- Contact Hero Section -->
<section class="contact-hero py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="page-title mb-3">
                <i class="fas fa-envelope-open-text text-danger me-2"></i>Na Kontaktoni
            </h1>
            <p class="lead text-muted">Jemi këtu për t'ju ndihmuar. Dërgoni mesazhin tuaj dhe do t'ju përgjigjemi sa më shpejt!</p>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-page py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Information Sidebar -->
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <div class="contact-info-header">
                        <h2 class="mb-4">
                            <i class="fas fa-info-circle text-danger me-2"></i>Informacione Kontakti
                        </h2>
                        <p class="text-muted">Na gjeni në adresën tonë ose na kontaktoni përmes kanaleve të mëposhtme:</p>
                    </div>
                    
                    <div class="contact-items">
                        <div class="contact-item-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Adresa</h4>
                                <p>Rruga Iliria, Nr. 123<br>Tiranë, Shqipëri</p>
                            </div>
                        </div>
                        
                        <div class="contact-item-card">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Telefon</h4>
                                <p><a href="tel:+35541234567">+355 4 123 4567</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <p><a href="mailto:info@iliryantv.com">info@iliryantv.com</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item-card">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Orari i Punës</h4>
                                <p>E Hënë - E Premte: 08:00 - 20:00<br>
                                   E Shtunë - E Dielë: 09:00 - 18:00</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Albanian Pride Section -->
                    <div class="albanian-pride-box mt-4">
                        <div class="pride-content">
                            <i class="fas fa-flag text-danger mb-2"></i>
                            <h5>Krenar për të qenë Shqiptar!</h5>
                            <p class="small mb-0">Bashkohu me komunitetin tonë dhe ndaj mendimet të tua me ne.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <div class="form-header mb-4">
                        <h2 class="mb-2">
                            <i class="fas fa-paper-plane text-danger me-2"></i>Dërgo Mesazh
                        </h2>
                        <p class="text-muted">Plotësoni formularin më poshtë dhe ne do t'ju kontaktojmë brenda 24 orëve.</p>
                    </div>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="contact-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                           placeholder="Emri i Plotë" required>
                                    <label for="name"><i class="fas fa-user me-2"></i>Emri i Plotë *</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           placeholder="Email" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Email *</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mt-3">
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                                   placeholder="Subjekti" required>
                            <label for="subject"><i class="fas fa-tag me-2"></i>Subjekti *</label>
                        </div>
                        
                        <div class="form-floating mt-3">
                            <textarea class="form-control" id="message" name="message" 
                                      style="height: 200px;" placeholder="Mesazhi" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            <label for="message"><i class="fas fa-comment-alt me-2"></i>Mesazhi *</label>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Dërgo Mesazhin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>