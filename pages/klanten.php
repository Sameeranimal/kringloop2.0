<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Klanten beheer pagina
*/

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";
require_once "../klasses/klant.php";

$database = new Database();
$db = $database->connect();
$klantClass = new Klant($db);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'] ?? '';
    
    if ($actie === 'toevoegen') {
        $naam = $_POST['naam'] ?? '';
        $adres = $_POST['adres'] ?? '';
        $plaats = $_POST['plaats'] ?? '';
        $telefoon = $_POST['telefoon'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if ($klantClass->toevoegen($naam, $adres, $plaats, $telefoon, $email)) {
            $message = "Klant succesvol toegevoegd!";
        } else {
            $message = "Fout bij toevoegen.";
        }
    }
    
    if ($actie === 'verwijderen') {
        $id = $_POST['id'] ?? '';
        if ($klantClass->verwijderen($id)) {
            $message = "Klant verwijderd!";
        }
    }
}

$zoekterm = $_GET['zoek'] ?? '';
if (!empty($zoekterm)) {
    $klanten = $klantClass->zoekKlanten($zoekterm);
} else {
    $klanten = $klantClass->getAlleKlanten();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Klanten Beheer</title>
</head>
<body>
    <h1>Klanten Beheer</h1>
    <p><a href="dashboard.php">Dashboard</a></p>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <h3>Nieuwe Klant Toevoegen</h3>
    <form method="POST">
        <input type="hidden" name="actie" value="toevoegen">
        <input type="text" name="naam" placeholder="Naam" required>
        <input type="text" name="adres" placeholder="Adres" required>
        <input type="text" name="plaats" placeholder="Plaats" required>
        <input type="text" name="telefoon" placeholder="Telefoon" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Toevoegen</button>
    </form>
    
    <hr>
    
    <h3>Klanten Zoeken</h3>
    <form method="GET">
        <input type="text" name="zoek" placeholder="Zoek op naam, email of telefoon" value="<?php echo htmlspecialchars($zoekterm); ?>">
        <button type="submit">Zoeken</button>
        <?php if (!empty($zoekterm)): ?>
            <a href="klanten.php">Alles tonen</a>
        <?php endif; ?>
    </form>
    
    <hr>
    
    <h3>Alle Klanten</h3>
    
    <?php if (empty($klanten)): ?>
        <p>Geen klanten gevonden.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Adres</th>
                <th>Plaats</th>
                <th>Telefoon</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
            <?php foreach ($klanten as $klant): ?>
                <tr>
                    <td><?php echo $klant['id']; ?></td>
                    <td><?php echo htmlspecialchars($klant['naam']); ?></td>
                    <td><?php echo htmlspecialchars($klant['adres']); ?></td>
                    <td><?php echo htmlspecialchars($klant['plaats']); ?></td>
                    <td><?php echo htmlspecialchars($klant['telefoon']); ?></td>
                    <td><?php echo htmlspecialchars($klant['email']); ?></td>
                    <td>
                        <a href="klant_wijzigen.php?id=<?php echo $klant['id']; ?>">Wijzigen</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="actie" value="verwijderen">
                            <input type="hidden" name="id" value="<?php echo $klant['id']; ?>">
                            <button type="submit" onclick="return confirm('Zeker?')">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
