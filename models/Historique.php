<?php 
require_once __DIR__ . '/../db/database.php';

class Historique {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Récupérer les candidatures envoyées où la dateDebut est passée
    public function fetchSentHistory($userId) {
        try {
            $sql = "SELECT A.titre_annonce, A.dateDebut_annonce, U.prenom_utilisateur, U.nom_utilisateur 
                    FROM postule P 
                    JOIN annonce A ON P.Id_Annonce = A.Id_Annonce 
                    JOIN utilisateur U ON A.Id_utilisateur = U.Id_utilisateur 
                    WHERE P.Id_utilisateur = :userId AND A.dateDebut_annonce < CURDATE()";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur fetchSentHistory : ' . $e->getMessage());
            return [];
        }
    }

    // Récupérer les candidatures reçues où la dateDebut est passée
    public function fetchReceivedHistory($userId) {
        try {
            $sql = "SELECT A.titre_annonce, A.dateDebut_annonce, U.prenom_utilisateur, U.nom_utilisateur 
                    FROM postule P 
                    JOIN utilisateur U ON P.Id_utilisateur = U.Id_utilisateur 
                    JOIN annonce A ON P.Id_Annonce = A.Id_Annonce 
                    WHERE A.Id_utilisateur = :userId AND A.dateDebut_annonce < CURDATE()";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur fetchReceivedHistory : ' . $e->getMessage());
            return [];
        }
    }
}
?>