<?php
require_once __DIR__ . '/../db/database.php';

class Annonce {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT a.*, u.prenom_utilisateur, u.nom_utilisateur, ad.latitude, ad.longitude
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON u.Id_Adresse = ad.Id_Adresse";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des annonces : ' . $e->getMessage());
            return [];
        }
    }

    public function fetchByUserType($type) {
        try {
            $sql = "SELECT a.*, u.prenom_utilisateur, u.nom_utilisateur, ad.latitude, ad.longitude
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON u.Id_Adresse = ad.Id_Adresse
                    WHERE u.type_utilisateur = :type";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':type', $type, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des annonces par type: ' . $e->getMessage());
            return [];
        }
    }
}
?>
