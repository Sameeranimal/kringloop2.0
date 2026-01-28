<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Verkoop registratie pagina
*/

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";
require_once "../klasses/verkoop.php";
require_once "../klasses/klant.php";
require_once "../klasses/artikel.php";

$database = new Database();
$db = $database->connect();
$verkoopClass = new Verkoop($db);
$klantClass = new Klant($db);
$artikelClass = new Artikel($db);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'] ?? '';
    
    if ($actie === 'registreren') {
        $klant_id = $_POST['klant_id'] ?? '';
        $artikel_id = $_POST['artikel_id'] ?? '';
        
        if ($verkoopClass->registreren($klant_id, $artikel_id)) {
            $message = "Verkoop geregistreerd!";
        } else {
            $message = "Fout bij registreren.";
        }
    }
}

$klanten = $klantClass->getAlleKlanten();
$artikelen = $artikelClass->getAlleArtikelen();
$verkopen = $verkoopClass->getAlleVerkopen();
$totaal = $verkoopClass->getTotaalOpbrengst();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Verkoop Registratie</title>
</head>
<body>
    <h1>Verkoop Registratie</h1>
    <p><a href="dashboard.php">Dashboard</a></p>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <h3>Artikel Verkopen</h3>
    <form method="POST">
        <input type="hidden" name="actie" value="registreren">
        
        <label>Klant:</label><br>
        <select name="klant_id" required>
            <option>-- Kies klant --</option>
            <?php foreach ($klanten as $klant): ?>
                <option value="<?php echo $klant['id']; ?>">
                    <?php echo htmlspecialchars($klant['naam']); ?> - <?php echo htmlspecialchars($klant['email']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label>Artikel:</label><br>
        <select name="artikel_id" required>
            <option>-- Kies artikel --</option>
            <?php foreach ($artikelen as $artikel): ?>
                <option value="<?php echo $artikel['id']; ?>">
                    <?php echo htmlspecialchars($artikel['naam']); ?> - € <?php echo number_format($artikel['prijs_ex_btw'], 2, ',', '.'); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <button type="submit">Verkoop Registreren</button>
    </form>
    
    <hr>
    
    <h3>Verkoop Overzicht</h3>
    
    <p><strong>Totaal opbrengst: € <?php echo number_format($totaal, 2, ',', '.'); ?></strong></p>
    
    <?php if (empty($verkopen)): ?>
        <p>Geen verkopen geregistreerd.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Klant</th>
                <th>Artikel</th>
                <th>Prijs</th>
                <th>Verkocht op</th>
            </tr>
            <?php foreach ($verkopen as $verkoop): ?>
                <tr>
                    <td><?php echo $verkoop['id']; ?></td>
                    <td><?php echo htmlspecialchars($verkoop['klant_naam']); ?></td>
                    <td><?php echo htmlspecialchars($verkoop['artikel_naam']); ?></td>
                    <td>€ <?php echo number_format($verkoop['prijs_ex_btw'], 2, ',', '.'); ?></td>
                    <td><?php echo substr($verkoop['verkocht_op'], 0, 10); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
