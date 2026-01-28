<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Verkoop class voor verkoop registratie
*/

class Verkoop {
    public $conn;
    public $table = "verkoop";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function registreren($klant_id, $artikel_id) {
        $sql = "INSERT INTO {$this->table} (klant_id, artikel_id, verkocht_op) 
                VALUES (:klant_id, :artikel_id, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':klant_id' => $klant_id,
            ':artikel_id' => $artikel_id
        ]);
    }
    
    public function getAlleVerkopen() {
        $sql = "SELECT v.*, k.naam as klant_naam, a.naam as artikel_naam, a.prijs_ex_btw 
                FROM {$this->table} v 
                LEFT JOIN klant k ON v.klant_id = k.id 
                LEFT JOIN artikel a ON v.artikel_id = a.id 
                ORDER BY v.verkocht_op DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVerkopenPerPeriode($van, $tot) {
        $sql = "SELECT v.*, k.naam as klant_naam, a.naam as artikel_naam, a.prijs_ex_btw 
                FROM {$this->table} v 
                LEFT JOIN klant k ON v.klant_id = k.id 
                LEFT JOIN artikel a ON v.artikel_id = a.id 
                WHERE DATE(v.verkocht_op) BETWEEN :van AND :tot 
                ORDER BY v.verkocht_op DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':van' => $van,
            ':tot' => $tot
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotaalOpbrengst() {
        $sql = "SELECT SUM(a.prijs_ex_btw) as totaal 
                FROM {$this->table} v 
                LEFT JOIN artikel a ON v.artikel_id = a.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['totaal'] ?? 0;
    }
}
?>
