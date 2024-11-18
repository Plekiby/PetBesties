<?php
require_once __DIR__ . '/../models/Prestataire.php';

class PrestataireController {
    private $model;

    public function __construct() {
        $this->model = new Prestataire();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $prestataires = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $prestataires;
    }
}
?>
