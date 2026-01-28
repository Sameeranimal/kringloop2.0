<?php
session_start();
require_once "../config/db.php";
require_once "../klasses/login.php";

$message = "";
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $token = $_POST['token'] ?? '';
    
    if ($newPassword !== $confirmPassword) {
        $message = "Wachtwoorden komen niet overeen.";
    } else {
        $database = new Database();
        $db = $database->connect();
        $loginClass = new login($db);
        
        if ($loginClass->resetPassword($token, $newPassword)) {
            $message = "Wachtwoord succesvol gereset! U kunt nu inloggen.";
        } else {
            $message = "Ongeldige of verlopen token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Resetten</title>
</head>
<body>
    <h1>Wachtwoord Resetten</h1>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <form method="POST" action="reset_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        
        <div>
            <label for="password">Nieuw Wachtwoord:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        
        <br>
        
        <div>
            <label for="confirm_password">Bevestig Wachtwoord:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <br>
        
        <button type="submit">Wachtwoord Resetten</button>
    </form>
    
    <br>
    
    <p>
        <a href="login.php">Terug naar inloggen</a>
    </p>
</body>
</html>
