<?php
require_once __DIR__ . '/../models/adresse.php'; // Chemin vers votre modèle adresse

class AdresseController {

    public function fetchAdressesByUser($userId) {
        $adresseModel = new Adresse();
        return $adresseModel->getUserAddresses($userId);
    }

    // Dans AdresseController.php
    public function createAdresse($userId, $numero, $rue, $ville, $complement, $latitude, $longitude) {
    $adresseModel = new Adresse();
    $adresseId = $adresseModel->createAdresse($numero, $rue, $ville, $complement, $latitude, $longitude);

    if ($adresseId) {
        // Maintenant, insérer dans utilisateur_adresse
        return $adresseModel->linkUserToAdresse($userId, $adresseId);
    }
    return false;
}

}
