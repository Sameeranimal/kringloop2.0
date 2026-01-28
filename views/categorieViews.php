<?php
session_start();
if(!isset($_SESSION['user_id']) || ($_SESSION['rol'] != 'Directie')) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';
require_once '../klasses/categorie.php';

$database = new Database();
$db = $database->connect();
$categorie = new Categorie($db);

$message = '';

// Verwerk acties
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'create') {
            $categorie->categorie = htmlspecialchars($_POST['categorie']);
            if($categorie->create()) {
                $message = "Categorie toegevoegd.";
            }
        } elseif($_POST['action'] == 'update') {
            $categorie->id = $_POST['id'];
            $categorie->categorie = htmlspecialchars($_POST['categorie']);
            if($categorie->update()) {
                $message = "Categorie bijgewerkt.";
            }
        } elseif($_POST['action'] == 'delete') {
            $categorie->id = $_POST['id'];
            if($categorie->delete()) {
                $message = "Categorie verwijderd.";
            }
        }
    }
}

// Edit mode
$edit_mode = false;
$edit_data = null;
if(isset($_GET['edit'])) {
    $edit_mode = true;
    $categorie->id = $_GET['edit'];
    $categorie->readOne();
    $edit_data = $categorie;
}

// Haal alle categorieën op
$stmt = $categorie->readAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Categorieën Beheren</title>
</head>
<body>
    <h1>Categorieën Beheren</h1>
    <p><a href="../index.php">Terug naar Dashboard</a></p>
    
    <?php if($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>
    
    <h2><?php echo $edit_mode ? 'Categorie Bewerken' : 'Nieuwe Categorie Toevoegen'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'create'; ?>">
        <?php if($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data->id; ?>">
        <?php endif; ?>
        <table>
            <tr>
                <td>Categorie naam:</td>
                <td><input type="text" name="categorie" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->categorie) : ''; ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit"><?php echo $edit_mode ? 'Bijwerken' : 'Toevoegen'; ?></button>
                    <?php if($edit_mode): ?>
                        <a href="categorieViews.php"><button type="button">Annuleren</button></a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
    
    <h2>Categorieën Overzicht</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Categorie</th>
            <th>Acties</th>
        </tr>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['categorie']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Bewerken</a> |
                <form method="POST" style="display:inline;" onsubmit="return confirm('Weet u zeker dat u deze categorie wilt verwijderen?');">
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
