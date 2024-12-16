<?php
require_once __DIR__ . '/../models/Postule.php';

class PostuleController {
    private $model;

    public function __construct() {
        $this->model = new Postule();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $candidatures = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $candidatures;
    }
}
?>
