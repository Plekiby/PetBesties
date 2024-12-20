<?php
require_once __DIR__ . '/../db/database.php';

class Candidature {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Crée une nouvelle candidature.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param int $annonceId L'ID de l'annonce.
     * @return bool True si la création est réussie, false sinon.
     */
    public function createCandidature($userId, $annonceId) {
        try {
            $sql = "INSERT INTO candidature (Id_utilisateur, Id_Annonce, date_candidature) VALUES (:user_id, :annonce_id, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':annonce_id', $annonceId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de la candidature : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un utilisateur a déjà postulé à une annonce.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param int $annonceId L'ID de l'annonce.
     * @return bool True si l'utilisateur a déjà postulé, false sinon.
     */
    public function hasUserPostulated($userId, $annonceId) {
        try {
            $sql = "SELECT COUNT(*) FROM candidature WHERE Id_utilisateur = :user_id AND Id_Annonce = :annonce_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':annonce_id', $annonceId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Erreur lors de la vérification de la candidature : ' . $e->getMessage());
            return false;
        }
    }
}
?>
