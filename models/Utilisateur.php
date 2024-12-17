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
            $sql = "SELECT prenom_utilisateur, nom_utilisateur, email_utilisateur, telephone_utilisateur FROM utilisateur WHERE Id_utilisateur = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    public function create($prenom, $nom, $email, $mdp, $telephone, $type, $rib, $adresseId) {
        try {
            $sql = "INSERT INTO utilisateur (prenom_utilisateur, nom_utilisateur, email_utilisateur, mdp_utilisateur, telephone_utilisateur, type_utilisateur, rib_utilisateur, Id_Adresse) 
                    VALUES (:prenom, :nom, :email, :mdp, :telephone, :type, :rib, :adresseId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mdp', $mdp);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':type', $type, PDO::PARAM_INT);
            $stmt->bindParam(':rib', $rib);
            $stmt->bindParam(':adresseId', $adresseId, PDO::PARAM_INT);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage();
            return false;
        }
    }

    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'utilisateur par email : ' . $e->getMessage());
            return false;
        }
    }

    public function fetchOne($id) {
        try {
            $sql = "SELECT * FROM utilisateur WHERE Id_utilisateur = $id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des utilisateurs : ' . $e->getMessage());
            return [];
        }
    }


    public function beginTransaction() {
        $this->conn->beginTransaction();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function rollBack() {
        $this->conn->rollBack();
    }
}
?>
