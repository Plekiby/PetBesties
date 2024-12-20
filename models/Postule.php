<?php 
require_once __DIR__ . '/../db/database.php';

class Postule {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Récupérer les candidatures envoyées (pour "Mes candidatures", uniquement futures)
    public function fetchSent($userId) {
        try {
            $sql = "SELECT A.*, U.*, P.date_postule, P.etat_postule 
                    FROM postule P 
                    JOIN utilisateur U ON P.Id_utilisateur = U.Id_utilisateur 
                    JOIN annonce A ON P.Id_Annonce = A.Id_Annonce 
                    WHERE P.Id_utilisateur = :userId 
                    AND A.dateDebut_annonce >= CURDATE()"; // Candidatures futures
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des candidatures envoyées : ' . $e->getMessage());
            return [];
        }
    }

    // Méthode pour ajouter une nouvelle candidature
    public function create($userId, $annonceId) {
        try {
            $sql = "INSERT INTO postule (Id_utilisateur, Id_Annonce, date_postule, etat_postule) 
                    VALUES (:userId, :annonceId, NOW(), 'En attente')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':annonceId', $annonceId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de la candidature : ' . $e->getMessage());
            return false;
        }
    }

    public function hasAlreadyApplied($userId, $annonceId) {
        try {
            $sql = "SELECT COUNT(*) FROM postule WHERE Id_utilisateur = :userId AND Id_Annonce = :annonceId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':annonceId', $annonceId, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (PDOException $e) {
            error_log('Erreur lors de la vérification de la candidature : ' . $e->getMessage());
            return false;
        }
    }

    // Récupérer les candidatures reçues (pour "Mes candidatures", uniquement futures)
    public function fetchReceived($userId) {
        try {
            $sql = "SELECT A.*, U.*, P.date_postule, P.etat_postule 
                    FROM postule P 
                    JOIN utilisateur U ON P.Id_utilisateur = U.Id_utilisateur 
        try {
                    JOIN annonce A ON P.Id_Annonce = A.Id_Annonce 
                    WHERE A.Id_utilisateur = :userId 
                    AND A.dateDebut_annonce >= CURDATE()"; // Candidatures futures
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des candidatures reçues : ' . $e->getMessage());
            return [];
        }
    }
}
?>

