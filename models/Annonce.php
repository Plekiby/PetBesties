<?php
require_once __DIR__ . '/../db/database.php';

class Annonce {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT A.*, U.nom_utilisateur, U.prenom_utilisateur FROM annonce A  NATURAL JOIN utilisateur U";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des annonces : ' . $e->getMessage());
            return [];
        }
    }
}
?>
