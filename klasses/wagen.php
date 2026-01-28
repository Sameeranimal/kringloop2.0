<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Wagen class - beheer voertuigen
*/

class Wagen {
    private $conn;
    private $table = "wagen";
    
    public $id;
    public $kenteken;
    public $merk;
    public $type;
    public $kleur;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Voeg nieuwe wagen toe
    public function toevoegen($kenteken, $merk, $type, $kleur = null) {
        $sql = "INSERT INTO {$this->table} (kenteken, merk, type, kleur)
                VALUES (:kenteken, :merk, :type, :kleur)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':kenteken', $kenteken);
        $stmt->bindParam(':merk', $merk);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':kleur', $kleur);
        
        return $stmt->execute();
    }

    // Haal alle wagens op
    public function getAlleWagens() {
        $sql = "SELECT * FROM {$this->table} ORDER BY kenteken";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Haal wagen op via ID
    public function getWagenById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Haal wagen op via kenteken
    public function getWagenByKenteken($kenteken) {
        $sql = "SELECT * FROM {$this->table} WHERE kenteken = :kenteken";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':kenteken', $kenteken);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Wijzig wagen
    public function wijzigen($id, $kenteken, $merk, $type, $kleur = null) {
        $sql = "UPDATE {$this->table} 
                SET kenteken = :kenteken, merk = :merk, type = :type, kleur = :kleur
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':kenteken', $kenteken);
        $stmt->bindParam(':merk', $merk);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':kleur', $kleur);
        
        return $stmt->execute();
    }

    // Verwijder wagen
    public function verwijderen($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
