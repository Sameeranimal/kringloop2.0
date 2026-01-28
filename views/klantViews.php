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
require_once '../klasses/klant.php';

$database = new Database();
$db = $database->connect();
$klant = new Klant($db);

$message = '';

// Verwerk acties
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'create') {
            $klant->naam = htmlspecialchars($_POST['naam']);
            $klant->adres = htmlspecialchars($_POST['adres']);
            $klant->plaats = htmlspecialchars($_POST['plaats']);
            $klant->telefoon = htmlspecialchars($_POST['telefoon']);
            $klant->email = htmlspecialchars($_POST['email']);
            if($klant->create()) {
                $message = "Klant toegevoegd.";
            }
        } elseif($_POST['action'] == 'update') {
            $klant->id = $_POST['id'];
            $klant->naam = htmlspecialchars($_POST['naam']);
            $klant->adres = htmlspecialchars($_POST['adres']);
            $klant->plaats = htmlspecialchars($_POST['plaats']);
            $klant->telefoon = htmlspecialchars($_POST['telefoon']);
            $klant->email = htmlspecialchars($_POST['email']);
            if($klant->update()) {
                $message = "Klant bijgewerkt.";
            }
        } elseif($_POST['action'] == 'delete') {
            $klant->id = $_POST['id'];
            if($klant->delete()) {
                $message = "Klant verwijderd.";
            }
        }
    }
}

// Edit mode
$edit_mode = false;
$edit_data = null;
if(isset($_GET['edit'])) {
    $edit_mode = true;
    $klant->id = $_GET['edit'];
    $klant->readOne();
    $edit_data = $klant;
}

// Haal alle klanten op
$stmt = $klant->readAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Klanten Beheren</title>
</head>
<body>
    <h1>Klanten Beheren</h1>
    <p><a href="../index.php">Terug naar Dashboard</a></p>
    
    <?php if($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>
    
    <h2><?php echo $edit_mode ? 'Klant Bewerken' : 'Nieuwe Klant Toevoegen'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'create'; ?>">
        <?php if($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data->id; ?>">
        <?php endif; ?>
        <table>
            <tr>
                <td>Naam:</td>
                <td><input type="text" name="naam" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->naam) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Adres:</td>
                <td><input type="text" name="adres" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->adres) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Plaats:</td>
                <td><input type="text" name="plaats" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->plaats) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Telefoon:</td>
                <td><input type="tel" name="telefoon" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->telefoon) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="<?php echo $edit_mode ? htmlspecialchars($edit_data->email) : ''; ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit"><?php echo $edit_mode ? 'Bijwerken' : 'Toevoegen'; ?></button>
                    <?php if($edit_mode): ?>
                        <a href="klantViews.php"><button type="button">Annuleren</button></a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
    
    <h2>Klanten Overzicht</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Plaats</th>
            <th>Telefoon</th>
            <th>Email</th>
            <th>Acties</th>
        </tr>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['naam']); ?></td>
            <td><?php echo htmlspecialchars($row['adres']); ?></td>
            <td><?php echo htmlspecialchars($row['plaats']); ?></td>
            <td><?php echo htmlspecialchars($row['telefoon']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Bewerken</a> |
                <form method="POST" style="display:inline;" onsubmit="return confirm('Weet u zeker dat u deze klant wilt verwijderen?');">
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
