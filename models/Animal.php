<?php
require_once __DIR__ . '/../db/database.php';

class Animal {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAllByUser($userId) {
        try {
            $sql = "SELECT Id_Animal, nom_animal, race_animal, age_animal, info_animal FROM animal WHERE Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des animaux : ' . $e->getMessage());
            return [];
        }
    }

    public function getAnimalById($animalId, $userId) {
        try {
            $sql = "SELECT Id_Animal, nom_animal, race_animal, age_animal, info_animal FROM animal WHERE Id_Animal = :animalId AND Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'animal : ' . $e->getMessage());
            return false;
        }
    }

    public function createAnimal($userId, $nomAnimal, $raceAnimal, $ageAnimal, $infoAnimal) {
        try {
            $sql = "INSERT INTO animal (nom_animal, race_animal, age_animal, info_animal, Id_utilisateur) VALUES (:nom, :race, :age, :info, :userId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $nomAnimal);
            $stmt->bindParam(':race', $raceAnimal);
            $stmt->bindParam(':age', $ageAnimal, PDO::PARAM_INT);
            $stmt->bindParam(':info', $infoAnimal);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'animal : ' . $e->getMessage());
            return false;
        }
    }

    public function updateAnimal($userId, $animalId, $nomAnimal, $raceAnimal, $ageAnimal, $infoAnimal) {
        try {
            $sql = "UPDATE animal SET nom_animal = :nom, race_animal = :race, age_animal = :age, info_animal = :info WHERE Id_Animal = :animalId AND Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $nomAnimal);
            $stmt->bindParam(':race', $raceAnimal);
            $stmt->bindParam(':age', $ageAnimal, PDO::PARAM_INT);
            $stmt->bindParam(':info', $infoAnimal);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la mise à jour de l\'animal : ' . $e->getMessage());
            return false;
        }
    }

    public function deleteAnimal($userId, $animalId) {
        try {
            $sql = "DELETE FROM animal WHERE Id_Animal = :animalId AND Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de l\'animal : ' . $e->getMessage());
            return false;
        }
    }
}
?>
