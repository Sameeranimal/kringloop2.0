<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Inlogpagina voor medewerkers
*/

session_start();
require_once "../config/db.php";
require_once "../klasses/login.php";
require_once "../vendor/autoload.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    
    $database = new Database();
    $db = $database->connect();
    $loginClass = new login($db);
    
    $gebruiker = $loginClass->login($gebruikersnaam, $wachtwoord);
    
    if ($gebruiker) {
        if ($gebruiker['is_geverifieerd'] == 1) {
            $_SESSION['user_id'] = $gebruiker['id'];
            $_SESSION['gebruikersnaam'] = $gebruiker['gebruikersnaam'];
            $_SESSION['rollen'] = $gebruiker['rollen'];
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Uw account is geblokkeerd.";
        }
    } else {
        $message = "Ongeldige gebruikersnaam of wachtwoord.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen - Kringloop Centrum Duurzaam</title>
</head>
<body>
    <h1>Medewerkers Inloggen</h1>
    <h2>Kringloop Centrum Duurzaam</h2>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Gebruikersnaam:</label><br>
        <input type="text" name="gebruikersnaam" required><br><br>
        
        <label>Wachtwoord:</label><br>
        <input type="password" name="wachtwoord" required><br><br>
        
        <button type="submit">Inloggen</button>
    </form>
    
    <p><a href="forgot_password.php">Wachtwoord vergeten?</a></p>
</body>
</html>
