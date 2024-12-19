<?php
require_once __DIR__ . '/../models/animal.php'; // Adapter le chemin selon votre structure
class AnimalController {

    // Récupérer les informations de l'animal de l'utilisateur
    /*public function fetchAnimal($userId) {
        // Exemple de code pour récupérer les infos de l'animal de la base de données
        $db = new Database();
        $query = "SELECT * FROM animal WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }*/
   
    // Récupérer un seul animal (si besoin)
    public function fetchAnimals($userId) {
        // Ancienne méthode, si vous en avez besoin
        // mais ici on suppose qu'on utilise fetchAnimals.
        // Si vous n'en avez pas l'utilité, vous pouvez supprimer cette méthode.
        $animalModel = new Animal();
        $animals = $animalModel->fetchAllByUser($userId);
        return !empty($animals) ? $animals[0] : null; 
    }
 
    // Mettre à jour les informations de l'animal (si nécessaire)

    // Mettre à jour les informations de l'animal
    public function updateAnimal($userId, $data) {
        $db = new Database();
        $query = "UPDATE animal SET nom_animal = :nom, race_animal = :race, infos_animal = :infos, age_animal = :age WHERE user_id = :user_id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':nom', $data['nom_animal']);
        $stmt->bindParam(':race', $data['race_animal']);
        $stmt->bindParam(':infos', $data['infos_animal']);
        $stmt->bindParam(':age', $data['age_animal']);
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();
    }
}

