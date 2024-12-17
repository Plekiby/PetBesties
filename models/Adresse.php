<?php
require_once __DIR__ . '/../db/database.php';

class Adresse {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO adresse (numero_adresse, rue_adresse, nom_adresse, complement_adresse, latitude, longitude) 
                    VALUES (:numero, :rue, :nom, :complement, :latitude, :longitude)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':numero', $data['numero']);
            $stmt->bindParam(':rue', $data['rue']);
            $stmt->bindParam(':nom', $data['nom']);
            $stmt->bindParam(':complement', $data['complement']);
            $stmt->bindParam(':latitude', $data['latitude']);
            $stmt->bindParam(':longitude', $data['longitude']);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'adresse : ' . $e->getMessage());
            return false;
        }
    }
}
?>