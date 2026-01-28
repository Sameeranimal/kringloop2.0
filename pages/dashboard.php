<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Dashboard voor ingelogde medewerkers
*/

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rollen'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard Kringloop Centrum Duurzaam</h1>
    
    <p>Welkom, <?php echo htmlspecialchars($_SESSION['gebruikersnaam']); ?>!</p>
    <p>Uw rol: <?php echo htmlspecialchars($rol); ?></p>
    
    <hr>
    
    <h3>Menu</h3>
    
    <?php if ($rol === 'directie'): ?>
        <h4>Directie Functies</h4>
        <ul>
            <li><a href="gebruikers.php">Gebruikersbeheer</a></li>
            <li><a href="rapportages.php">Maandoverzichten</a></li>
        </ul>
    <?php endif; ?>
    
    <?php if ($rol === 'directie' || $rol === 'winkel'): ?>
        <h4>Klanten & Verkoop</h4>
        <ul>
            <li><a href="klanten.php">Klanten Beheer</a></li>
            <li><a href="verkoop.php">Verkoop Registratie</a></li>
            <li><a href="verkoop_overzicht.php">Opbrengst Verkopen</a></li>
        </ul>
    <?php endif; ?>
    
    <?php if ($rol === 'directie' || $rol === 'magazijn'): ?>
        <h4>Voorraad & Artikelen</h4>
        <ul>
            <li><a href="categorieën.php">Categorieën Beheer</a></li>
            <li><a href="artikelen.php">Artikelen Beheer</a></li>
            <li><a href="voorraad.php">Voorraad Beheer</a></li>
        </ul>
    <?php endif; ?>
    
    <?php if ($rol === 'directie' || $rol === 'chauffeur' || $rol === 'winkel'): ?>
        <h4>Planning</h4>
        <ul>
            <li><a href="planning.php">Ritplanning</a></li>
        </ul>
    <?php endif; ?>
    
    <hr>
    
    <p>
        <a href="logout.php">Uitloggen</a>
    </p>
</body>
</html>
