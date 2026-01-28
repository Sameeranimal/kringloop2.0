<?php
/*
naam script     : database.php
omschrijving    : hier s de code van de database connectie met oop programming
Auteur          : hussen
project         : netfish
Aanmaakdatum    : 12/01/2025
*/ 
class Database {
    private $host = "localhost";
    private $dbname = "duurzaam";
    private $username = "root";
    private $password = "";

    public function connect() {
        try {
            return new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->username,
                $this->password
            );
        } catch (PDOException $e) {
            die("Fout met de database: " . $e->getMessage());
        }
    }
}
?>