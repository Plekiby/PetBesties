
<?php
require_once __DIR__ . '/../db/database.php';

class Promenade {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT * FROM promenade";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des promenades : ' . $e->getMessage());
            return [];
        }
    }
}
?>