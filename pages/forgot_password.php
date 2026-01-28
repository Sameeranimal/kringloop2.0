<?php
session_start();
require_once "../config/db.php";
require_once "../klasses/login.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    $database = new Database();
    $db = $database->connect();
    $loginClass = new login($db);
    
    if ($loginClass->requestPasswordReset($email)) {
        $message = "Als dit email adres bestaat, ontvangt u een link om uw wachtwoord te resetten.";
    } else {
        $message = "Er is iets misgegaan. Probeer het opnieuw.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Vergeten</title>
</head>
<body>
    <h1>Wachtwoord Vergeten</h1>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <p>Voer uw email adres in om een link te ontvangen om uw wachtwoord te resetten.</p>
    
    <form method="POST" action="forgot_password.php">
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>
        
        <br>
        
        <button type="submit">Reset Link Versturen</button>
    </form>
    
    <br>
    
    <p>
        <a href="login.php">Terug naar inloggen</a>
    </p>
</body>
</html>
