<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Personen class - beheer donateurs/leveranciers
*/

class Personen {
    private $conn;
    private $table = "personen";
    
    public $id;
    public $voornaam;
    public $achternaam;
    public $adres;
    public $plaats;
    public $telefoon;
    public $email;
    public $geboortedatum;
    public $datum_ingevoerd;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Voeg nieuwe persoon toe
    public function toevoegen($voornaam, $achternaam, $adres, $plaats, $telefoon, $email, $geboortedatum) {
        $sql = "INSERT INTO {$this->table} (voornaam, achternaam, adres, plaats, telefoon, email, geboortedatum)
                VALUES (:voornaam, :achternaam, :adres, :plaats, :telefoon, :email, :geboortedatum)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':voornaam', $voornaam);
        $stmt->bindParam(':achternaam', $achternaam);
        $stmt->bindParam(':adres', $adres);
        $stmt->bindParam(':plaats', $plaats);
        $stmt->bindParam(':telefoon', $telefoon);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':geboortedatum', $geboortedatum);
        
        return $stmt->execute();
    }

    // Haal alle personen op
    public function getAllePersonen() {
        $sql = "SELECT * FROM {$this->table} ORDER BY achternaam, voornaam";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Haal persoon op via ID
    public function getPersonById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Zoek personen op naam
    public function zoekPersonen($zoekterm) {
        $zoekterm = "%{$zoekterm}%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE voornaam LIKE :zoek OR achternaam LIKE :zoek OR email LIKE :zoek OR telefoon LIKE :zoek
                ORDER BY achternaam, voornaam";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':zoek', $zoekterm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Wijzig persoon
    public function wijzigen($id, $voornaam, $achternaam, $adres, $plaats, $telefoon, $email, $geboortedatum) {
        $sql = "UPDATE {$this->table} 
                SET voornaam = :voornaam, achternaam = :achternaam, adres = :adres, plaats = :plaats,
                    telefoon = :telefoon, email = :email, geboortedatum = :geboortedatum
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':voornaam', $voornaam);
        $stmt->bindParam(':achternaam', $achternaam);
        $stmt->bindParam(':adres', $adres);
        $stmt->bindParam(':plaats', $plaats);
        $stmt->bindParam(':telefoon', $telefoon);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':geboortedatum', $geboortedatum);
        
        return $stmt->execute();
    }

    // Verwijder persoon
    public function verwijderen($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
