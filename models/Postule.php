<?php
require_once __DIR__ . '/../db/database.php';

class Postule {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchSent($userId) {
        try {
            $sql = "SELECT A.*, U.*, P.date_postule, P.etat_postule FROM postule P 
                    JOIN utilisateur U ON P.Id_utilisateur = U.Id_utilisateur 
                    JOIN annonce A ON P.Id_Annonce = A.Id_Annonce 
                    WHERE P.Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des candidatures envoyées : ' . $e->getMessage());
            return [];
        }
    }

    public function fetchReceived($userId) {
        try {
            $sql = "SELECT A.*, U.*, P.date_postule, P.etat_postule FROM postule P 
                    JOIN utilisateur U ON P.Id_utilisateur = U.Id_utilisateur 
                    JOIN annonce A ON P.Id_Annonce = A.Id_Annonce 
                    WHERE A.Id_utilisateur = :userId";
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
