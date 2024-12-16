
<?php
require_once __DIR__ . '/../db/database.php';

class Adresse {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create($adresseData) {
        try {
            $sql = "INSERT INTO adresse (numero_adresse, rue_adresse, nom_adresse, complement_adresse, latitude, longitude)
                    VALUES (:numero, :rue, :nom, :complement, :latitude, :longitude)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':numero', $adresseData['numero']);
            $stmt->bindParam(':rue', $adresseData['rue']);
            $stmt->bindParam(':nom', $adresseData['nom']);
            $stmt->bindParam(':complement', $adresseData['complement']);
            $stmt->bindParam(':latitude', $adresseData['latitude']);
            $stmt->bindParam(':longitude', $adresseData['longitude']);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo 'Erreur lors de la création de l\'adresse : ' . $e->getMessage();
            return false;
        }
    }
}
?>