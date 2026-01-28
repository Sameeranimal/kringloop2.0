<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Verkoop overzicht pagina met periode filtering
*/

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";
require_once "../klasses/verkoop.php";

$database = new Database();
$db = $database->connect();
$verkoopClass = new Verkoop($db);

$van = $_GET['van'] ?? date('Y-m-01');
$tot = $_GET['tot'] ?? date('Y-m-d');
$verkopen = [];
$totaal = 0;

if (!empty($van) && !empty($tot)) {
    $verkopen = $verkoopClass->getVerkopenPerPeriode($van, $tot);
    
    // Bereken totale opbrengst
    foreach ($verkopen as $v) {
        $totaal += $v['prijs_ex_btw'];
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Opbrengst Verkopen</title>
</head>
<body>
    <h1>Opbrengst Verkopen</h1>
    <p><a href="dashboard.php">Dashboard</a></p>
    
    <h3>Filter op Periode</h3>
    <form method="GET">
        <label>Van:</label>
        <input type="date" name="van" value="<?php echo htmlspecialchars($van); ?>" required>
        
        <label>Tot:</label>
        <input type="date" name="tot" value="<?php echo htmlspecialchars($tot); ?>" required>
        
        <button type="submit">Filteren</button>
    </form>
    
    <hr>
    
    <h3>Verkoop Overzicht</h3>
    <p><strong>Periode: <?php echo $van; ?> tot <?php echo $tot; ?></strong></p>
    <p><strong>Totaal opbrengst: € <?php echo number_format($totaal, 2, ',', '.'); ?></strong></p>
    
    <?php if (empty($verkopen)): ?>
        <p>Geen verkopen in deze periode.</p>
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
