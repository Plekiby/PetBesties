<?php
require_once __DIR__ . '/../db/database.php';

class Utilisateur {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT * FROM utilisateur";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des utilisateurs : ' . $e->getMessage());
            return [];
        }
    }

    public function selectOne($id) {
        try {
            $sql = "SELECT prenom_utilisateur, nom_utilisateur FROM utilisateur WHERE Id_utilisateur = $id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des utilisateurs : ' . $e->getMessage());
            return [];
        }
    }
}
?>
