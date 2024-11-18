<?php
class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db = 'petbesties';
    private $user = 'admin';
    private $pass = 'SN2x@XukD7fzHQz/';

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
