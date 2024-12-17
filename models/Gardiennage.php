
<?php
require_once __DIR__ . '/../db/database.php';

class Gardiennage {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAll() {
        try {
            $sql = "SELECT * FROM gardiennage";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des gardiennages : ' . $e->getMessage());
            return [];
        }
    }
}
?>