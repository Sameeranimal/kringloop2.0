<?php
class Categorie {
    private $conn;
    private $table = 'categorie';
    
    public $id;
    public $categorie;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create
    public function create() {
        $query = "INSERT INTO " . $this->table . " (categorie) VALUES (:categorie)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categorie', $this->categorie);
        return $stmt->execute();
    }
    
    // Read all
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY categorie";
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
            $this->categorie = $row['categorie'];
            return true;
        }
        return false;
    }
    
    // Update
    public function update() {
        $query = "UPDATE " . $this->table . " SET categorie = :categorie WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categorie', $this->categorie);
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
}
?>
