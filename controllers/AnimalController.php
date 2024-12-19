<?php
require_once __DIR__ . '/../models/animal.php';

class AnimalController {

    public function fetchAnimals($userId) {
        $animalModel = new Animal();
        return $animalModel->fetchAllByUser($userId);
    }

    public function addAnimal($userId, $nomAnimal, $raceAnimal) {
        $animalModel = new Animal();
        return $animalModel->createAnimal($userId, $nomAnimal, $raceAnimal);
    }
}
