<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Persoonsgegevens beheer pagina
*/

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";
require_once "../klasses/personen.php";

$database = new Database();
$db = $database->connect();
$persoonClass = new Personen($db);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actie = $_POST['actie'] ?? '';
    
    if ($actie === 'toevoegen') {
        $voornaam = $_POST['voornaam'] ?? '';
        $achternaam = $_POST['achternaam'] ?? '';
        $adres = $_POST['adres'] ?? '';
        $plaats = $_POST['plaats'] ?? '';
        $telefoon = $_POST['telefoon'] ?? '';
        $email = $_POST['email'] ?? '';
        $geboortedatum = $_POST['geboortedatum'] ?? '';
        
        if ($persoonClass->toevoegen($voornaam, $achternaam, $adres, $plaats, $telefoon, $email, $geboortedatum)) {
            $message = "Persoon succesvol toegevoegd!";
        } else {
            $message = "Fout bij toevoegen.";
        }
    }
    
    if ($actie === 'verwijderen') {
        $id = $_POST['id'] ?? '';
        if ($persoonClass->verwijderen($id)) {
            $message = "Persoon verwijderd!";
        }
    }
}

$zoekterm = $_GET['zoek'] ?? '';
if (!empty($zoekterm)) {
    $personen = $persoonClass->zoekPersonen($zoekterm);
} else {
    $personen = $persoonClass->getAllePersonen();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registratie Persoonsgegevens</title>
</head>
<body>
    <h1>Registratie Persoonsgegevens</h1>
    <p><a href="dashboard.php">Dashboard</a></p>
    
    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
    
    <h3>Nieuwe Persoon Registreren</h3>
    <form method="POST">
        <input type="hidden" name="actie" value="toevoegen">
        <input type="text" name="voornaam" placeholder="Voornaam" required>
        <input type="text" name="achternaam" placeholder="Achternaam" required>
        <input type="text" name="adres" placeholder="Adres" required>
        <input type="text" name="plaats" placeholder="Plaats" required>
        <input type="text" name="telefoon" placeholder="Telefoon" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="date" name="geboortedatum" required>
        <button type="submit">Registreren</button>
    </form>
    
    <hr>
    
    <h3>Personen Zoeken</h3>
    <form method="GET">
        <input type="text" name="zoek" placeholder="Zoek op naam, email of telefoon" value="<?php echo htmlspecialchars($zoekterm); ?>">
        <button type="submit">Zoeken</button>
        <?php if (!empty($zoekterm)): ?>
            <a href="persoonsgegevens.php">Alles tonen</a>
        <?php endif; ?>
    </form>
    
    <hr>
    
    <h3>Alle Personen</h3>
    
    <?php if (empty($personen)): ?>
        <p>Geen personen gevonden.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Adres</th>
                <th>Plaats</th>
                <th>Telefoon</th>
                <th>Email</th>
                <th>Geboortedatum</th>
                <th>Datum Ingevoerd</th>
                <th>Acties</th>
            </tr>
            <?php foreach ($personen as $persoon): ?>
                <tr>
                    <td><?php echo $persoon['id']; ?></td>
                    <td><?php echo htmlspecialchars($persoon['voornaam']); ?></td>
                    <td><?php echo htmlspecialchars($persoon['achternaam']); ?></td>
                    <td><?php echo htmlspecialchars($persoon['adres']); ?></td>
                    <td><?php echo htmlspecialchars($persoon['plaats']); ?></td>
                    <td><?php echo htmlspecialchars($persoon['telefoon']); ?></td>
                    <td><?php echo htmlspecialchars($persoon['email']); ?></td>
                    <td><?php echo substr($persoon['geboortedatum'], 0, 10); ?></td>
                    <td><?php echo substr($persoon['datum_ingevoerd'], 0, 10); ?></td>
                    <td>
                        <a href="persoon_wijzigen.php?id=<?php echo $persoon['id']; ?>">Wijzigen</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="actie" value="verwijderen">
                            <input type="hidden" name="id" value="<?php echo $persoon['id']; ?>">
                            <button type="submit" onclick="return confirm('Zeker?')">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
