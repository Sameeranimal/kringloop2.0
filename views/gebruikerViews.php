<?php
session_start();
if(!isset($_SESSION['user_id']) || ($_SESSION['rol'] != 'Directie')) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';
require_once '../klasses/gebruiker.php';

$database = new Database();
$db = $database->connect();
$gebruiker = new Gebruiker($db);

$message = '';

// Verwerk acties
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'create') {
            if($gebruiker->create(
                htmlspecialchars($_POST['gebruikersnaam']),
                $_POST['wachtwoord'],
                htmlspecialchars($_POST['rol'])
            )) {
                $message = "Gebruiker toegevoegd.";
            }
        } elseif($_POST['action'] == 'blokkeren') {
            if($gebruiker->blokkeren($_POST['id'])) {
                $message = "Gebruiker geblokkeerd.";
            }
        } elseif($_POST['action'] == 'deblokkeren') {
            if($gebruiker->deblokkeren($_POST['id'])) {
                $message = "Gebruiker gedeblokkeerd.";
            }
        }
    }
}

// Haal alle gebruikers op
$stmt = $gebruiker->readAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Gebruikers Beheren</title>
</head>
<body>
    <h1>Gebruikers Beheren</h1>
    <p><a href="../index.php">Terug naar Dashboard</a></p>
    
    <?php if($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>
    
    <h2>Nieuwe Gebruiker Toevoegen</h2>
    <form method="POST">
        <input type="hidden" name="action" value="create">
        <table>
            <tr>
                <td>Gebruikersnaam:</td>
                <td><input type="text" name="gebruikersnaam" required></td>
            </tr>
            <tr>
                <td>Wachtwoord:</td>
                <td><input type="password" name="wachtwoord" required></td>
            </tr>
            <tr>
                <td>Rol:</td>
                <td>
                    <select name="rol" required>
                        <option value="Directie">Directie</option>
                        <option value="Magazijnmedewerker">Magazijnmedewerker</option>
                        <option value="Winkelpersoneel">Winkelpersoneel</option>
                        <option value="Chauffeur">Chauffeur</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Toevoegen</button></td>
            </tr>
        </table>
    </form>
    
    <h2>Gebruikers Overzicht</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Gebruikersnaam</th>
            <th>Rol</th>
            <th>Status</th>
            <th>Actie</th>
        </tr>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['gebruikersnaam']); ?></td>
            <td><?php echo htmlspecialchars($row['rollen']); ?></td>
            <td><?php echo $row['is_geverifieerd'] ? 'Actief' : 'Geblokkeerd'; ?></td>
            <td>
                <?php if($row['is_geverifieerd']): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="blokkeren">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Blokkeren</button>
                    </form>
                <?php else: ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="deblokkeren">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Deblokkeren</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
