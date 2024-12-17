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

    public function createAnnonce($titre, $description, $dateDebut, $duree, $tarif, $id_utilisateur, $gardiennage, $promenade, $adresseId) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO annonce (titre_annonce, description_annonce, dateDebut_annonce, duree_annonce, tarif_annonce, Id_Statut, Id_utilisateur, datePublication_annonce, Id_Adresse) 
                    VALUES (:titre, :description, :dateDebut, :duree, :tarif, :id_statut, :id_utilisateur, NOW(), :adresseId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':dateDebut', $dateDebut);
            $stmt->bindParam(':duree', $duree);
            $stmt->bindParam(':tarif', $tarif);
            $stmt->bindValue(':id_statut', 1, PDO::PARAM_INT); // Assuming '1' is the default status
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':adresseId', $adresseId, PDO::PARAM_INT);
            $stmt->execute();
            $annonceId = $this->conn->lastInsertId();

            // Insert into peut_etre table
            $sqlPeutEtre = "INSERT INTO peut_etre (Id_Annonce, Id_Gardiennage, Id_Promenade) 
                            VALUES (:annonceId, :gardiennage, :promenade)";
            $stmtPeutEtre = $this->conn->prepare($sqlPeutEtre);
            $stmtPeutEtre->bindParam(':annonceId', $annonceId, PDO::PARAM_INT);
            $stmtPeutEtre->bindParam(':gardiennage', $gardiennage, PDO::PARAM_INT);
            $stmtPeutEtre->bindParam(':promenade', $promenade, PDO::PARAM_INT);
            $stmtPeutEtre->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('Erreur lors de la création de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }
}
?>
