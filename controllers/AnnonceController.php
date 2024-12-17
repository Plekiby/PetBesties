<?php
require_once __DIR__ . '/../models/Annonce.php';

class AnnonceController {
    private $model;

    public function __construct() {
        $this->model = new Annonce();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $annonces = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $annonces;
    }

    public function getAnnoncesByType($type) {
        // Appeler le modèle pour récupérer les données filtrées
        $annonces = $this->model->fetchByUserType($type);

        // Retourner les données pour la vue
        return $annonces;
    }
}
?>
