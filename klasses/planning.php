<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Planning class voor ritplanning
*/

class Planning {
    public $conn;
    public $table = "planning";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function toevoegen($artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op) {
        $sql = "INSERT INTO {$this->table} (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op) 
                VALUES (:artikel_id, :klant_id, :kenteken, :ophalen_of_bezorgen, :afspraak_op)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':artikel_id' => $artikel_id,
            ':klant_id' => $klant_id,
            ':kenteken' => $kenteken,
            ':ophalen_of_bezorgen' => $ophalen_of_bezorgen,
            ':afspraak_op' => $afspraak_op
        ]);
    }
    
    public function getAlleRitten() {
        $sql = "SELECT p.*, k.naam as klant_naam, k.adres, k.plaats, a.naam as artikel_naam 
                FROM {$this->table} p 
                LEFT JOIN klant k ON p.klant_id = k.id 
                LEFT JOIN artikel a ON p.artikel_id = a.id 
                ORDER BY p.afspraak_op";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRittenPerKenteken($kenteken) {
        $sql = "SELECT p.*, k.naam as klant_naam, k.adres, k.plaats, a.naam as artikel_naam 
                FROM {$this->table} p 
                LEFT JOIN klant k ON p.klant_id = k.id 
                LEFT JOIN artikel a ON p.artikel_id = a.id 
                WHERE p.kenteken = :kenteken 
                ORDER BY p.afspraak_op";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':kenteken' => $kenteken]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRittenPerDatum($datum) {
        $sql = "SELECT p.*, k.naam as klant_naam, k.adres, k.plaats, a.naam as artikel_naam 
                FROM {$this->table} p 
                LEFT JOIN klant k ON p.klant_id = k.id 
                LEFT JOIN artikel a ON p.artikel_id = a.id 
                WHERE DATE(p.afspraak_op) = :datum 
                ORDER BY p.afspraak_op";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':datum' => $datum]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function wijzigen($id, $kenteken, $afspraak_op) {
        $sql = "UPDATE {$this->table} 
                SET kenteken = :kenteken, 
                    afspraak_op = :afspraak_op 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':kenteken' => $kenteken,
            ':afspraak_op' => $afspraak_op
        ]);
    }
    
    public function verwijderen($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
