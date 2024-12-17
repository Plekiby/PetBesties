<?php
require_once __DIR__ . '/../db/database.php';

class Adresse {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT * FROM adresse";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des adresses : ' . $e->getMessage());
            return [];
        }
    }

    public function createAdresse($numero, $rue, $nom, $complement, $latitude, $longitude) {
        try {
            $sql = "INSERT INTO adresse (numero_adresse, rue_adresse, nom_adresse, complement_adresse, latitude, longitude) 
                    VALUES (:numero, :rue, :nom, :complement, :latitude, :longitude)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':rue', $rue);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':complement', $complement);
            $stmt->bindParam(':latitude', $latitude);
            $stmt->bindParam(':longitude', $longitude);
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'adresse : ' . $e->getMessage());
            return false;
        }
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
            return $this->conn->lastInsertId(); // Retourner l'ID de l'adresse créée
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'adresse : ' . $e->getMessage());
            return false;
        }
    }
}
?>