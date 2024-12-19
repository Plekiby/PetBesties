<?php
require_once __DIR__ . '/../db/database.php';

class Animal {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

   
    

    // ...additional methods as needed...
 
    public function fetchAllByUser($userId) {
        try {
            $sql = "SELECT Id_Animal, nom_animal, race_animal FROM animal WHERE Id_utilisateur = :userId";
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
            $sql = "SELECT Id_Animal, nom_animal, race_animal FROM animal WHERE Id_Animal = :animalId AND Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l animal : ' . $e->getMessage());
            return false;
        }
    }
 
    public function createAnimal($userId, $nomAnimal, $raceAnimal) {
        try {
            $sql = "INSERT INTO animal (nom_animal, race_animal, Id_utilisateur) VALUES (:nom, :race, :userId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $nomAnimal);
            $stmt->bindParam(':race', $raceAnimal);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'animal : ' . $e->getMessage());
            return false;
        }
    }



 
    // Récupérer tous les animaux de l'utilisateur
    public function fetchAnimals($userId) {
        $animalModel = new Animal();
        return $animalModel->fetchAllByUser($userId);
    }
    // Récupérer un seul animal (si besoin)
    public function fetchAnimal($userId) {
        // Ancienne méthode, si vous en avez besoin
        // mais ici on suppose qu'on utilise fetchAnimals.
        // Si vous n'en avez pas l'utilité, vous pouvez supprimer cette méthode.
        $animalModel = new Animal();
        $animals = $animalModel->fetchAllByUser($userId);
        return !empty($animals) ? $animals[0] : null; 
    }
 
    // Mettre à jour les informations de l'animal (si nécessaire)
    public function updateAnimal($userId, $data) {
        $db = new Database();
        $query = "UPDATE animal SET nom_animal = :nom, race_animal = :race, infos_animal = :infos, age_animal = :age WHERE Id_utilisateur = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_animal']);
        $stmt->bindParam(':race', $data['race_animal']);
        $stmt->bindParam(':infos', $data['infos_animal']);
        $stmt->bindParam(':age', $data['age_animal']);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }
}
?>