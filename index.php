<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Entry point - redirect naar login of dashboard
*/

session_start();

if (isset($_SESSION['user_id'])) {
    // Ingelogd? Ga naar dashboard
    header("Location: pages/dashboard.php");
} else {
    // Niet ingelogd? Ga naar login
    header("Location: pages/login.php");
}
exit();

    
    $user = $loginClass->login($email, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['naam'] = $user['naam'];
        $_SESSION['email'] = $user['email'];
        header("Location: pages/dashboard.php");
        exit();
    } else {
        $message = "Ongeldig email of wachtwoord.";
    }

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
</head>
<body>
    <h1>Inloggen</h1>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>
        
        <br>
        
        <div>
            <label for="password">Wachtwoord:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        
        <br>
        
        <button type="submit">Inloggen</button>
    </form>
    
    <br>
    
    <p>
        <a href="forgot_password.php">Wachtwoord vergeten?</a>
    </p>
    
    <p>
        Nog geen account? <a href="register.php">Registreren</a>
    </p>
</body>
</html>
