<?php
require_once 'includes/config.php';
require_once 'includes/user_auth.php';

// Shkyç userin
userLogout();

// Ridrejto në faqen kryesore
header('Location: index.php');
exit();
?>