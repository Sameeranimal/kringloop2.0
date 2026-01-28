<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$rol = $_SESSION['rol'];
if($rol != 'Directie' && $rol != 'Winkelpersoneel') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';
require_once '../klasses/artikel.php';
require_once '../klasses/categorie.php';

$database = new Database();
$db = $database->connect();
$artikel = new Artikel($db);
$categorie = new Categorie($db);

$message = '';

// Verwerk acties
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'create') {
            $artikel->categorie_id = htmlspecialchars($_POST['categorie_id']);
            $artikel->naam = htmlspecialchars($_POST['naam']);
            $artikel->prijs_ex_btw = htmlspecialchars($_POST['prijs_ex_btw']);
            if($artikel->create()) {
                $message = "Artikel toegevoegd.";
            }
        } elseif($_POST['action'] == 'update') {
            $artikel->id = $_POST['id'];
            $artikel->categorie_id = htmlspecialchars($_POST['categorie_id']);
            $artikel->naam = htmlspecialchars($_POST['naam']);
            $artikel->prijs_ex_btw = htmlspecialchars($_POST['prijs_ex_btw']);
            if($artikel->update()) {
                $message = "Artikel bijgewerkt.";
            }
        } elseif($_POST['action'] == 'delete') {
            $artikel->id = $_POST['id'];
            if($artikel->delete()) {
                $message = "Artikel verwijderd.";
            }
        }
    }
}

// Edit mode
$edit_mode = false;
$edit_data = null;
if(isset($_GET['edit'])) {
    $edit_mode = true;
    $artikel->id = $_GET['edit'];
    $artikel->readOne();
    $edit_data = $artikel;
}

// Haal alle artikelen op
$stmt = $artikel->readAll();
$categoriestmt = $categorie->readAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Artikelen Beheren</title>
</head>
<body>
    <h1>Artikelen Beheren</h1>
    <p><a href="../index.php">Terug naar Dashboard</a></p>
    
    <?php if($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>
    
    <h2><?php echo $edit_mode ? 'Artikel Bewerken' : 'Nieuw Artikel Toevoegen'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'create'; ?>">
        <?php if($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data->id; ?>">
        <?php endif; ?>
        <table>
            <tr>
                <td>Categorie:</td>
                <td>
                    <select name="categorie_id" required>
                        <option value="">Selecteer een categorie</option>
                        <?php 
                        $categoriestmt->execute();
                        while($cat = $categoriestmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($edit_mode && $edit_data->categorie_id == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['categorie']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Naam:</td>
                <td><input type="text" name="naam" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->naam) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Prijs (ex. BTW):</td>
                <td><input type="number" step="0.01" name="prijs_ex_btw" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->prijs_ex_btw) : ''; ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit"><?php echo $edit_mode ? 'Bijwerken' : 'Toevoegen'; ?></button>
                    <?php if($edit_mode): ?>
                        <a href="artikelViews.php"><button type="button">Annuleren</button></a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
    
    <h2>Artikelen Overzicht</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Categorie</th>
            <th>Naam</th>
            <th>Prijs (ex. BTW)</th>
            <th>Acties</th>
        </tr>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['categorie_naam'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['naam']); ?></td>
            <td>&euro; <?php echo number_format($row['prijs_ex_btw'], 2, ',', '.'); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Bewerken</a> |
                <form method="POST" style="display:inline;" onsubmit="return confirm('Weet u zeker dat u dit artikel wilt verwijderen?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Verwijderen</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
