<?php
/*
Versie          : 1.0
Datum           : 28 januari 2026
Omschrijving    : Gebruiker class voor gebruikersbeheer door directie
*/

class Gebruiker {
    public $conn;
    public $table = "gebruiker";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function toevoegen($gebruikersnaam, $wachtwoord, $rollen, $email) {
        $hashed = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $sql = "INSERT INTO {$this->table} (gebruikersnaam, wachtwoord, rollen, is_geverifieerd, email) 
                VALUES (:gebruikersnaam, :wachtwoord, :rollen, 1, :email)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':gebruikersnaam' => $gebruikersnaam,
            ':wachtwoord' => $hashed,
            ':rollen' => $rollen,
            ':email' => $email
        ]);
    }
    
    public function getAlleGebruikers() {
        $sql = "SELECT * FROM {$this->table} ORDER BY gebruikersnaam";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getGebruikerById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function wijzigen($id, $gebruikersnaam, $rollen, $email, $is_geverifieerd) {
        $sql = "UPDATE {$this->table} 
                SET gebruikersnaam = :gebruikersnaam, 
                    rollen = :rollen, 
                    email = :email,
                    is_geverifieerd = :is_geverifieerd 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':gebruikersnaam' => $gebruikersnaam,
            ':rollen' => $rollen,
            ':email' => $email,
            ':is_geverifieerd' => $is_geverifieerd
        ]);
    }
    
    public function blokkeren($id) {
        $sql = "UPDATE {$this->table} SET is_geverifieerd = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function deblokkeren($id) {
        $sql = "UPDATE {$this->table} SET is_geverifieerd = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function wachtwoordWijzigen($id, $nieuw_wachtwoord) {
        $hashed = password_hash($nieuw_wachtwoord, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET wachtwoord = :wachtwoord WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':wachtwoord' => $hashed
        ]);
    }
}
?>

// Child class: Magazijnmedewerker
class Magazijnmedewerker extends Gebruiker {
    public function getRol() {
        return "Magazijnmedewerker";
    }
    
    public function getPermissions() {
        return ['voorraad', 'artikelen'];
    }
}

// Child class: Winkelpersoneel
class Winkelpersoneel extends Gebruiker {
    public function getRol() {
        return "Winkelpersoneel";
    }
    
    public function getPermissions() {
        return ['artikelen', 'verkopen', 'klanten', 'planning_bekijken'];
    }
}

// Child class: Chauffeur
class Chauffeur extends Gebruiker {
    public function getRol() {
        return "Chauffeur";
    }
    
    public function getPermissions() {
        return ['planning'];
    }
}
?>
