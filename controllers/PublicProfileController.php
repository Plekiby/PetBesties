<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Animal.php';
require_once __DIR__ . '/../models/Annonce.php';

class PublicProfileController {
    private $utilisateurModel;
    private $animalModel;
    private $annonceModel;

    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
        $this->animalModel = new Animal();
        $this->annonceModel = new Annonce();
    }

    /**
     * Affiche le profil public d'un utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur dont le profil est affiché.
     */
    public function showProfile($userId) {
        // Démarrer la session si nécessaire
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Valider et nettoyer l'ID
        $userId = intval($userId);
        if ($userId <= 0) {
            http_response_code(400);
            echo "ID utilisateur invalide.";
            exit;
        }

        // Récupérer les données de l'utilisateur
        try {
            $utilisateur = $this->utilisateurModel->fetchOne($userId);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            exit;
        }

        if (!$utilisateur) {
            http_response_code(404);
            echo "Utilisateur non trouvé.";
            exit;
        }

        // Récupérer les animaux de l'utilisateur
        $animaux = $this->animalModel->fetchAllByUser($userId);

        // Récupérer les annonces de l'utilisateur
        $annonces = $this->annonceModel->fetchAnnoncesByUser($userId);

        // Inclure la vue avec les données
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/public_profile.php';
        include __DIR__ . '/../views/footer.php';
    }
}
?>
