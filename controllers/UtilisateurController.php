<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateurController {
    private $model;

    public function __construct() {
        $this->model = new Utilisateur();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $utilisateurs = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $utilisateurs;
    }

    public function selectOne() {
        // Appeler le modèle pour récupérer les données
        $utilisateur = $this->model->selectOne();

        // Retourner les données pour la vue
        return $utilisateur;
    }
}
?>
