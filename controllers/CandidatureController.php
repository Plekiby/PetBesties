<?php
require_once __DIR__ . '/../models/Postule.php';
require_once __DIR__ . '/../models/Annonce.php';

class CandidatureController {
    private $postuleModel;
    private $annonceModel;

    public function __construct() {
        $this->postuleModel = new Postule();
        $this->annonceModel = new Annonce();
    }

    /**
     * Gère la postulation d'un utilisateur à une annonce.
     *
     * @param int $userId ID de l'utilisateur postulant.
     * @param int $annonceId ID de l'annonce à laquelle postuler.
     * @return bool Résultat de la postulation.
     */
    public function postuler($userId, $annonceId) {
        // Vérifier si l'annonce existe et est active
        $annonce = $this->annonceModel->fetchOne($annonceId);
        if (!$annonce) {
            return false; // Annonce inexistante
        }

        // Vérifier si l'utilisateur a déjà postulé à cette annonce
        if ($this->postuleModel->hasAlreadyApplied($userId, $annonceId)) {
            return false; // Candidature déjà envoyée
        }

        // Créer une nouvelle candidature
        return $this->postuleModel->create($userId, $annonceId);
    }
}
?>
