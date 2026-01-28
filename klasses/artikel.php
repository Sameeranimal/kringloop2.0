<?php
class Artikel {
    private $conn;
    private $table = 'artikel';
    
    public $id;
    public $categorie_id;
    public $naam;
    public $prijs_ex_btw;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create
    public function create() {
        $query = "INSERT INTO " . $this->table . " (categorie_id, naam, prijs_ex_btw) VALUES (:categorie_id, :naam, :prijs_ex_btw)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categorie_id', $this->categorie_id);
        $stmt->bindParam(':naam', $this->naam);
        $stmt->bindParam(':prijs_ex_btw', $this->prijs_ex_btw);
        return $stmt->execute();
    }
    
    // Read all met categorie naam
    public function readAll() {
        $query = "SELECT a.*, c.categorie as categorie_naam 
                  FROM " . $this->table . " a 
                  LEFT JOIN categorie c ON a.categorie_id = c.id 
                  ORDER BY a.naam";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Read one
    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->categorie_id = $row['categorie_id'];
            $this->naam = $row['naam'];
            $this->prijs_ex_btw = $row['prijs_ex_btw'];
            return true;
        }
        return false;
    }
    
    // Update
    public function update() {
        $query = "UPDATE " . $this->table . " SET categorie_id = :categorie_id, naam = :naam, prijs_ex_btw = :prijs_ex_btw WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categorie_id', $this->categorie_id);
        $stmt->bindParam(':naam', $this->naam);
        $stmt->bindParam(':prijs_ex_btw', $this->prijs_ex_btw);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    
    // Delete
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    
    // Search
    public function search($keyword) {
        $query = "SELECT a.*, c.categorie as categorie_naam 
                  FROM " . $this->table . " a 
                  LEFT JOIN categorie c ON a.categorie_id = c.id 
                  WHERE a.naam LIKE :keyword OR a.id LIKE :keyword
                  ORDER BY a.naam";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt;
    }
}
?>
