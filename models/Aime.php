<?php
require_once __DIR__ . '/../db/database.php';

class Aime {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT annonce.titre_annonce,annonce.datePublication_annonce, utilisateur.nom_utilisateur, utilisateur.prenom_utilisateur, aime.*
            FROM annonce 
            JOIN utilisateur ON annonce.Id_utilisateur = utilisateur.Id_utilisateur 
            JOIN aime ON annonce.Id_utilisateur = aime.Id_Annonce
            WHERE aime.favoris = '1'"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des favoris : ' . $e->getMessage());
            return [];
        }
    }
}
?>
