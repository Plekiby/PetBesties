<?php
require_once __DIR__ . '/../models/Aime.php';

class AimeController {
    private $model;

    public function __construct() {
        $this->model = new Aime();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $favoris = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $favoris;
    }
}
?>
