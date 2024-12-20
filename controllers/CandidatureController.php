<?php
require_once __DIR__ . '/../models/Postule.php';

class CandidatureController {
    private $postuleModel;

    public function __construct() {
        $this->postuleModel = new Postule();
    }

    /**
     * Permet à un utilisateur de postuler à une annonce.
     *
     * @param int $userId L'ID de l'utilisateur qui postule.
     * @param int $annonceId L'ID de l'annonce à laquelle postuler.
     * @return bool True si la postulation est réussie, false sinon.
     */
    public function postuler($userId, $annonceId) {
        // Vérifier si l'utilisateur a déjà postulé à cette annonce
        if ($this->postuleModel->hasAlreadyApplied($userId, $annonceId)) {
            return false; // Déjà postulé
        }

        // Créer une nouvelle candidature
        return $this->postuleModel->create($userId, $annonceId);
    }
}
?>
