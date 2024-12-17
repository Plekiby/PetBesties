<?php
require_once __DIR__ . '/../db/database.php';

class Aime {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT A.*, U.* ,AI.* FROM aime AI  NATURAL JOIN utilisateur U NATURAL JOIN annonce A WHERE AI.favoris = 1";
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
