<?php
class Klant {
    private $conn;
    private $table = 'klant';
    
    public $id;
    public $naam;
    public $adres;
    public $plaats;
    public $telefoon;
    public $email;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create
    public function create() {
        $query = "INSERT INTO " . $this->table . " (naam, adres, plaats, telefoon, email) VALUES (:naam, :adres, :plaats, :telefoon, :email)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':naam', $this->naam);
        $stmt->bindParam(':adres', $this->adres);
        $stmt->bindParam(':plaats', $this->plaats);
        $stmt->bindParam(':telefoon', $this->telefoon);
        $stmt->bindParam(':email', $this->email);
        return $stmt->execute();
    }
    
    // Read all
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY naam";
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
            $this->naam = $row['naam'];
            $this->adres = $row['adres'];
            $this->plaats = $row['plaats'];
            $this->telefoon = $row['telefoon'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }
    
    // Update
    public function update() {
        $query = "UPDATE " . $this->table . " SET naam = :naam, adres = :adres, plaats = :plaats, telefoon = :telefoon, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':naam', $this->naam);
        $stmt->bindParam(':adres', $this->adres);
        $stmt->bindParam(':plaats', $this->plaats);
        $stmt->bindParam(':telefoon', $this->telefoon);
        $stmt->bindParam(':email', $this->email);
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
    
    // Read klant met verkochte artikelen
    public function readMetVerkopen() {
        $query = "SELECT k.*, a.naam as artikel_naam, v.verkocht_op 
                  FROM " . $this->table . " k
                  LEFT JOIN verkopen v ON k.id = v.klant_id
                  LEFT JOIN artikel a ON v.artikel_id = a.id
                  WHERE k.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>
