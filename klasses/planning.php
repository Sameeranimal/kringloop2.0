<?php

class Planning
{
    private $conn;
    private $table = 'planning';

    public $id;
    public $artikel_id;
    public $klant_id;
    public $kenteken;
    public $ophalen_of_bezorgen;
    public $afspraak_op;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Basis SELECT (hergebruik)
    private function baseSelect()
    {
        return "
            SELECT 
                p.*,
                k.naam   AS klant_naam,
                k.adres,
                k.plaats,
                a.naam   AS artikel_naam
            FROM {$this->table} p
            LEFT JOIN klant k   ON p.klant_id = k.id
            LEFT JOIN artikel a ON p.artikel_id = a.id
        ";
    }

    // Create
    public function create()
    {
        $query = "
            INSERT INTO {$this->table}
            (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op)
            VALUES
            (:artikel_id, :klant_id, :kenteken, :ophalen_of_bezorgen, :afspraak_op)
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':artikel_id'           => $this->artikel_id,
            ':klant_id'             => $this->klant_id,
            ':kenteken'             => $this->kenteken,
            ':ophalen_of_bezorgen'  => $this->ophalen_of_bezorgen,
            ':afspraak_op'          => $this->afspraak_op
        ]);
    }

    // Read all
    public function readAll()
    {
        $query = $this->baseSelect() . " ORDER BY p.afspraak_op ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read per kenteken
    public function readPerKenteken($kenteken)
    {
        $query = $this->baseSelect() . "
            WHERE p.kenteken = :kenteken
            ORDER BY p.afspraak_op ASC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':kenteken' => $kenteken]);
        return $stmt;
    }

    // Read per datum
    public function readPerDatum($datum)
    {
        $query = $this->baseSelect() . "
            WHERE DATE(p.afspraak_op) = :datum
            ORDER BY p.afspraak_op ASC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':datum' => $datum]);
        return $stmt;
    }

    // Read one
    public function readOne()
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute([':id' => $this->id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $this->artikel_id          = $row['artikel_id'];
        $this->klant_id            = $row['klant_id'];
        $this->kenteken            = $row['kenteken'];
        $this->ophalen_of_bezorgen = $row['ophalen_of_bezorgen'];
        $this->afspraak_op         = $row['afspraak_op'];

        return true;
    }

    // Update
    public function update()
    {
        $query = "
            UPDATE {$this->table}
            SET
                artikel_id = :artikel_id,
                klant_id = :klant_id,
                kenteken = :kenteken,
                ophalen_of_bezorgen = :ophalen_of_bezorgen,
                afspraak_op = :afspraak_op
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':artikel_id'           => $this->artikel_id,
            ':klant_id'             => $this->klant_id,
            ':kenteken'             => $this->kenteken,
            ':ophalen_of_bezorgen'  => $this->ophalen_of_bezorgen,
            ':afspraak_op'          => $this->afspraak_op,
            ':id'                   => $this->id
        ]);
    }

    // Delete
    public function delete()
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        return $stmt->execute([':id' => $this->id]);
    }
}
