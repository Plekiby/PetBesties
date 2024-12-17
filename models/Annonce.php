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
                    JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces : ' . $e->getMessage());
            return [];
        }
    }

    public function fetchByUserType($type) {
        try {
            $sql = "SELECT a.*, u.prenom_utilisateur, u.nom_utilisateur, ad.latitude, ad.longitude
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse
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

    public function createAnnonce($titre, $description, $dateDebut, $duree, $tarif, $id_utilisateur, $type, $details, $adresseId) {
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
            $stmt->bindValue(':id_statut', 1, PDO::PARAM_INT); // Statut par défaut
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':adresseId', $adresseId, PDO::PARAM_INT);
            $stmt->execute();
            $annonceId = $this->conn->lastInsertId();

            if ($type === 'promenade') {
                $sqlPromenade = "INSERT INTO promenade (Id_Promenade, details_promenade) VALUES (:annonceId, :details)";
                $stmtPromenade = $this->conn->prepare($sqlPromenade);
                $stmtPromenade->bindParam(':annonceId', $annonceId);
                $stmtPromenade->bindParam(':details', $details);
                $stmtPromenade->execute();
            } elseif ($type === 'gardiennage') {
                $sqlGardiennage = "INSERT INTO gardiennage (Id_Gardiennage, details_gardiennage) VALUES (:annonceId, :details)";
                $stmtGardiennage = $this->conn->prepare($sqlGardiennage);
                $stmtGardiennage->bindParam(':annonceId', $annonceId);
                $stmtGardiennage->bindParam(':details', $details);
                $stmtGardiennage->execute();
            }

            $this->conn->commit();
            return $annonceId;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('Erreur lors de la création de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }

    public function create($data) {
        // Si la méthode `create` n'est plus utilisée, envisagez de la supprimer pour éviter la confusion.
        // Sinon, assurez-vous qu'elle est cohérente avec son utilisation.
        try {
            $sql = "INSERT INTO annonce (titre_annonce, description_annonce, dateDebut_annonce, duree_annonce, tarif_annonce, Id_Statut, Id_utilisateur, type_annonce)
                    VALUES (:titre, :description, :dateDebut, :duree, :tarif, :statut, :utilisateur, :type)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titre', $data['titre_annonce']);
            $stmt->bindParam(':description', $data['description_annonce']);
            $stmt->bindParam(':dateDebut', $data['dateDebut_annonce']);
            $stmt->bindParam(':duree', $data['duree_annonce']);
            $stmt->bindParam(':tarif', $data['tarif_annonce']);
            $stmt->bindParam(':statut', $data['Id_Statut']);
            $stmt->bindParam(':utilisateur', $data['Id_utilisateur']);
            $stmt->bindParam(':type', $data['type_annonce']);
            $stmt->execute();
            $annonceId = $this->conn->lastInsertId();

            // Gérer les relations dans la table peut_etre
            if ($data['Id_Gardiennage'] && $data['Id_Promenade']) {
                $sql_relation = "INSERT INTO peut_etre (Id_Annonce, Id_Gardiennage, Id_Promenade) VALUES (:annonce, :gardiennage, :promenade)";
                $stmt_relation = $this->conn->prepare($sql_relation);
                $stmt_relation->bindParam(':annonce', $annonceId);
                $stmt_relation->bindParam(':gardiennage', $data['Id_Gardiennage']);
                $stmt_relation->bindParam(':promenade', $data['Id_Promenade']);
                $stmt_relation->execute();
            }

            return $annonceId;
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }

    public function getAnnonceById($id) {
        try {
            $sql = "SELECT a.*, u.prenom_utilisateur, u.nom_utilisateur, ad.numero_adresse, ad.rue_adresse, ad.nom_adresse
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse
                    WHERE a.Id_Annonce = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }
}
?>
