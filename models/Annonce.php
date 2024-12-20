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

    public function fetchFilteredAnnonces($type_utilisateur, $filters) {
        try {
            // Base de la requête
            $sql = "SELECT 
                        a.*, 
                        u.prenom_utilisateur, 
                        u.nom_utilisateur, 
                        u.Id_utilisateur, 
                        ad.latitude, 
                        ad.longitude, 
                        an.nom_animal, 
                        an.race_animal
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse
                    LEFT JOIN animal an ON a.Id_Animal = an.Id_Animal
                    WHERE u.type_utilisateur = :type_utilisateur";

            $params = ['type_utilisateur' => $type_utilisateur];

            // Filtrer par type d'annonce (Promenade, Gardiennage)
            if (!empty($filters['type_annonce'])) {
                $placeholders = [];
                foreach ($filters['type_annonce'] as $index => $type) {
                    $key = ":type_annonce_$index";
                    $placeholders[] = $key;
                    $params[$key] = $type;
                }
                $sql .= " AND a.type_annonce IN (" . implode(',', $placeholders) . ")";
            }

            // Filtrer par prix maximal
            if (!empty($filters['prix_max'])) {
                $sql .= " AND a.tarif_annonce <= :prix_max";
                $params[':prix_max'] = $filters['prix_max'];
            }

            // Filtrer par type d'animal
            if (!empty($filters['type_animal'])) {
                $placeholders = [];
                foreach ($filters['type_animal'] as $index => $type_animal) {
                    $key = ":type_animal_$index";
                    $placeholders[] = $key;
                    $params[$key] = $type_animal;
                }
                $sql .= " AND an.race_animal IN (" . implode(',', $placeholders) . ")";
            }

            // Préparer la requête
            $stmt = $this->conn->prepare($sql);

            // Liaison des paramètres
            foreach ($params as $key => $value) {
                if ($key === ':type_utilisateur') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } elseif (strpos($key, 'type_annonce_') === 0) {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } elseif (strpos($key, 'type_animal_') === 0) {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR); // Correction ici
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }

            // Exécution de la requête
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces filtrées : ' . $e->getMessage());
            return [];
        }
    }
    
    public function fetchAnnoncesByUser($userId) {
        try {
            $sql = "SELECT 
                        a.*, 
                        u.prenom_utilisateur, 
                        u.nom_utilisateur, 
                        ad.latitude, 
                        ad.longitude,
                        an.nom_animal, 
                        an.race_animal
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse
                    LEFT JOIN animal an ON a.Id_Animal = an.Id_Animal
                    WHERE a.Id_utilisateur = :user_id
                    ORDER BY a.datePublication_annonce DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces de l\'utilisateur : ' . $e->getMessage());
            return [];
        }
    }
    

    public function createAnnonce($titre, $description, $dateDebut, $duree, $tarif, $id_utilisateur, $type, $details, $adresseId, $animalId) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO annonce (titre_annonce, description_annonce, dateDebut_annonce, duree_annonce, tarif_annonce, Id_Statut, Id_utilisateur, datePublication_annonce, Id_Adresse, type_annonce, Id_Animal) 
                    VALUES (:titre, :description, :dateDebut, :duree, :tarif, :id_statut, :id_utilisateur, NOW(), :adresseId, :type_annonce, :animalId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':dateDebut', $dateDebut);
            $stmt->bindParam(':duree', $duree);
            $stmt->bindParam(':tarif', $tarif);
            $stmt->bindValue(':id_statut', 1, PDO::PARAM_INT); // Statut par défaut
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':adresseId', $adresseId, PDO::PARAM_INT);
            $stmt->bindParam(':type_annonce', $type, PDO::PARAM_STR);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->execute();
            $annonceId = $this->conn->lastInsertId();

            // Gérer les types d'annonce
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
    /**
     * Récupère une annonce spécifique par son ID.
     *
     * @param int $annonceId L'ID de l'annonce à récupérer.
     * @return array|false Les données de l'annonce ou false en cas d'erreur.
     */
    public function fetchOne($annonceId) {
        try {
            $sql = "SELECT 
                        a.*, 
                        u.prenom_utilisateur, 
                        u.nom_utilisateur, 
                        ad.latitude, 
                        ad.longitude,
                        an.nom_animal, 
                        an.race_animal
                    FROM annonce a
                    JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                    JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse
                    LEFT JOIN animal an ON a.Id_Animal = an.Id_Animal
                    WHERE a.Id_Annonce = :annonce_id
                    LIMIT 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':annonce_id', $annonceId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }
}
?>
