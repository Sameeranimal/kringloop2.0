<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Wagen beheer pagina
*/

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";
require_once "../klasses/wagen.php";

$database = new Database();
$db = $database->connect();
$wagenClass = new Wagen($db);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'] ?? '';
    
    if ($actie === 'toevoegen') {
        $kenteken = $_POST['kenteken'] ?? '';
        $merk = $_POST['merk'] ?? '';
        $type = $_POST['type'] ?? '';
        $kleur = $_POST['kleur'] ?? '';
        
        if ($wagenClass->toevoegen($kenteken, $merk, $type, $kleur)) {
            $message = "Wagen succesvol toegevoegd!";
        } else {
            $message = "Fout bij toevoegen (kenteken bestaat mogelijk al).";
        }
    }
    
    if ($actie === 'verwijderen') {
        $id = $_POST['id'] ?? '';
        if ($wagenClass->verwijderen($id)) {
            $message = "Wagen verwijderd!";
        }
    }
}

$wagens = $wagenClass->getAlleWagens();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wagen Beheer</title>
</head>
<body>
    <h1>Wagen Beheer</h1>
    <p><a href="dashboard.php">Dashboard</a></p>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <h3>Nieuwe Wagen Toevoegen</h3>
    <form method="POST">
        <input type="hidden" name="actie" value="toevoegen">
        <input type="text" name="kenteken" placeholder="Kenteken (bijv. AB-123-CD)" required>
        <input type="text" name="merk" placeholder="Merk" required>
        <input type="text" name="type" placeholder="Type" required>
        <input type="text" name="kleur" placeholder="Kleur">
        <button type="submit">Toevoegen</button>
    </form>
    
    <hr>
    
    <h3>Alle Wagens</h3>
    
    <?php if (empty($wagens)): ?>
        <p>Geen wagens beschikbaar.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Kenteken</th>
                <th>Merk</th>
                <th>Type</th>
                <th>Kleur</th>
                <th>Acties</th>
            </tr>
            <?php foreach ($wagens as $wagen): ?>
                <tr>
                    <td><?php echo $wagen['id']; ?></td>
                    <td><?php echo htmlspecialchars($wagen['kenteken']); ?></td>
                    <td><?php echo htmlspecialchars($wagen['merk']); ?></td>
                    <td><?php echo htmlspecialchars($wagen['type']); ?></td>
                    <td><?php echo htmlspecialchars($wagen['kleur'] ?? '-'); ?></td>
                    <td>
                        <a href="wagen_wijzigen.php?id=<?php echo $wagen['id']; ?>">Wijzigen</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="actie" value="verwijderen">
                            <input type="hidden" name="id" value="<?php echo $wagen['id']; ?>">
                            <button type="submit" onclick="return confirm('Zeker?')">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
