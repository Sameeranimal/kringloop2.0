<?php
session_start();
require_once "../config/db.php";
require_once "../klasses/login.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $adres = $_POST['adres'] ?? '';
    $plaats = $_POST['plaats'] ?? '';
    $telefoon = $_POST['telefoon'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($password !== $confirm_password) {
        $message = "Wachtwoorden komen niet overeen.";
    } else {
        $database = new Database();
        $db = $database->connect();
        $loginClass = new login($db);
        
        if ($loginClass->register($naam, $adres, $plaats, $telefoon, $email, $password)) {
            $message = "Account succesvol aangemaakt! Je kunt nu inloggen.";
        } else {
            $message = "Er is iets misgegaan. Probeer het opnieuw.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
</head>
<body>
    <h1>Registreren</h1>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <form method="POST" action="register.php">
        <div>
            <label for="naam">Naam:</label><br>
            <input type="text" id="naam" name="naam" required>
        </div>
        
        <br>
        
        <div>
            <label for="adres">Adres:</label><br>
            <input type="text" id="adres" name="adres" required>
        </div>
        
        <br>
        
        <div>
            <label for="plaats">Plaats:</label><br>
            <input type="text" id="plaats" name="plaats" required>
        </div>
        
        <br>
        
        <div>
            <label for="telefoon">Telefoon:</label><br>
            <input type="text" id="telefoon" name="telefoon" required>
        </div>
        
        <br>
        
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
        
        <div>
            <label for="confirm_password">Bevestig Wachtwoord:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <br>
        
        <button type="submit">Registreren</button>
    </form>
    
    <br>
    
    <p>
        Al een account? <a href="login.php">Inloggen</a>
    </p>
</body>
</html>
