<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Voorraad beheer pagina
*/

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";
require_once "../klasses/voorraad.php";
require_once "../klasses/artikel.php";
require_once "../klasses/status.php";

$database = new Database();
$db = $database->connect();
$voorraadClass = new Voorraad($db);
$artikelClass = new Artikel($db);
$statusClass = new Status($db);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'] ?? '';
    
    if ($actie === 'toevoegen') {
        $artikel_id = $_POST['artikel_id'] ?? '';
        $locatie = $_POST['locatie'] ?? '';
        $aantal = $_POST['aantal'] ?? '';
        $status_id = $_POST['status_id'] ?? '';
        
        if ($voorraadClass->toevoegen($artikel_id, $locatie, $aantal, $status_id)) {
            $message = "Voorraad item toegevoegd!";
        } else {
            $message = "Fout bij toevoegen.";
        }
    }
    
    if ($actie === 'verwijderen') {
        $id = $_POST['id'] ?? '';
        if ($voorraadClass->verwijderen($id)) {
            $message = "Voorraad item verwijderd!";
        }
    }
}

$voorraad = $voorraadClass->getAlleVoorraad();
$artikelen = $artikelClass->getAlleArtikelen();
$statussen = $statusClass->getAlleStatussen();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Voorraad Beheer</title>
</head>
<body>
    <h1>Voorraad Beheer</h1>
    <p><a href="dashboard.php">Dashboard</a></p>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <h3>Artikel Inboeken</h3>
    <form method="POST">
        <input type="hidden" name="actie" value="toevoegen">
        
        <label>Artikel:</label><br>
        <select name="artikel_id" required>
            <option>-- Kies artikel --</option>
            <?php foreach ($artikelen as $artikel): ?>
                <option value="<?php echo $artikel['id']; ?>">
                    <?php echo htmlspecialchars($artikel['naam']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label>Locatie:</label><br>
        <select name="locatie" required>
            <option>-- Kies locatie --</option>
            <option value="winkel">Winkel</option>
            <option value="magazijn">Magazijn</option>
            <option value="reparatie">Reparatie</option>
        </select><br><br>
        
        <label>Aantal:</label><br>
        <input type="number" name="aantal" min="1" required><br><br>
        
        <label>Status:</label><br>
        <select name="status_id" required>
            <option>-- Kies status --</option>
            <?php foreach ($statussen as $status): ?>
                <option value="<?php echo $status['id']; ?>">
                    <?php echo htmlspecialchars($status['status']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <button type="submit">Inboeken</button>
    </form>
    
    <hr>
    
    <h3>Voorraad Overzicht</h3>
    
    <?php if (empty($voorraad)): ?>
        <p>Geen voorraad items.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Artikel</th>
                <th>Categorie</th>
                <th>Locatie</th>
                <th>Aantal</th>
                <th>Status</th>
                <th>Ingeboekt op</th>
                <th>Acties</th>
            </tr>
            <?php foreach ($voorraad as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo htmlspecialchars($item['artikel_naam']); ?></td>
                    <td><?php echo htmlspecialchars($item['categorie']); ?></td>
                    <td><?php echo htmlspecialchars($item['locatie']); ?></td>
                    <td><?php echo $item['aantal']; ?></td>
                    <td><?php echo htmlspecialchars($item['status']); ?></td>
                    <td><?php echo substr($item['ingeboekt_op'], 0, 10); ?></td>
                    <td>
                        <a href="voorraad_wijzigen.php?id=<?php echo $item['id']; ?>">Wijzigen</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="actie" value="verwijderen">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit" onclick="return confirm('Zeker?')">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
