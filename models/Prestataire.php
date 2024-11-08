<?php
require_once __DIR__ . '/../db/database.php';

class Prestataire {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getFilteredPrestataires($filters) {
        $sql = "SELECT * FROM prestataires WHERE tarif <= :prix_max";
        $params = ['prix_max' => $filters['prix_max']];
    
        // Ajoutez des conditions pour chaque filtre si spécifié
        if ($filters['promenade'] && $filters['hebergement']) {
            $sql .= " AND (type_service LIKE :promenade OR type_service LIKE :hebergement)";
            $params['promenade'] = '%Promenade%';
            $params['hebergement'] = '%Hébergement%';
        } elseif ($filters['promenade']) {
            $sql .= " AND type_service LIKE :promenade";
            $params['promenade'] = '%Promenade%';
        } elseif ($filters['hebergement']) {
            $sql .= " AND type_service LIKE :hebergement";
            $params['hebergement'] = '%Hébergement%';
        }
    
        // Ajoutez les conditions pour les types d'animaux
        $animalConditions = [];
        if ($filters['types']['chien']) {
            $animalConditions[] = "animaux_acceptes LIKE :chien";
            $params['chien'] = '%Chien%';
        }
        if ($filters['types']['chat']) {
            $animalConditions[] = "animaux_acceptes LIKE :chat";
            $params['chat'] = '%Chat%';
        }
        if ($filters['types']['oiseau']) {
            $animalConditions[] = "animaux_acceptes LIKE :oiseau";
            $params['oiseau'] = '%Oiseau%';
        }
        if ($filters['types']['rongeur']) {
            $animalConditions[] = "animaux_acceptes LIKE :rongeur";
            $params['rongeur'] = '%Rongeur%';
        }
    
        // Ajoutez les conditions pour les animaux, s'ils existent
        if (!empty($animalConditions)) {
            $sql .= " AND (" . implode(' OR ', $animalConditions) . ")";
        }
    
        // Préparez la requête SQL
        $stmt = $this->conn->prepare($sql);
    
        // Liez les paramètres
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
    
        // Exécuter la requête
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
}
?>
