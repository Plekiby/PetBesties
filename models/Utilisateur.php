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

    public function create($prenom, $nom, $email, $mdp, $telephone, $type, $rib, $age, $code_postal) {
        try {
            // Log the data being inserted
            error_log("Creating user with prenom: $prenom, nom: $nom, email: $email, telephone: $telephone, type: $type, rib: $rib, age: $age, code_postal: $code_postal");

            $sql = "INSERT INTO utilisateur (prenom_utilisateur, nom_utilisateur, email_utilisateur, mdp_utilisateur, telephone_utilisateur, type_utilisateur, rib_utilisateur, age, code_postal) 
                    VALUES (:prenom, :nom, :email, :mdp, :telephone, :type, :rib, :age, :code_postal)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mdp', $mdp);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':type', $type, PDO::PARAM_INT);
            $stmt->bindParam(':rib', $rib);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT); // Assurez-vous que $age est défini ou utilisez une valeur par défaut
            $stmt->bindParam(':code_postal', $code_postal, PDO::PARAM_STR); // Assurez-vous que $code_postal est défini ou utilisez une valeur par défaut
            
            if ($stmt->execute()) {
                $newId = $this->conn->lastInsertId();
                error_log("User created successfully with ID: " . $newId);
                return $newId;
            } else {
                error_log("Failed to execute user creation query.");
                return false;
            }
        } catch (PDOException $e) {
            error_log('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
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
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la récupération des utilisateurs : ' . $e->getMessage());
            return [];
        }
    }

    public function update($id, $prenom, $nom, $email, $telephone) {
        try {
            $sql = "UPDATE utilisateur 
                    SET prenom_utilisateur = :prenom, 
                        nom_utilisateur = :nom, 
                        email_utilisateur = :email, 
                        telephone_utilisateur = :telephone 
                    WHERE Id_utilisateur = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password) {
        try {
            // Récupérer l'utilisateur par email
            $user = $this->getByEmail($email);
            if ($user && password_verify($password, $user['mdp_utilisateur'])) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Logger l'erreur en production au lieu d'afficher directement
            error_log('Erreur lors de la connexion de l\'utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    public function getUserAnimals($userId) {
        try {
            $sql = "SELECT Id_Animal, nom_animal, race_animal FROM animal WHERE Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des animaux : ' . $e->getMessage());
            return [];
        }
    }

    public function getUserAddresses($userId) {
        try {
            $sql = "SELECT adresse.Id_Adresse, adresse.numero_adresse, adresse.rue_adresse, adresse.nom_adresse 
                    FROM adresse
                    JOIN utilisateur_adresse ON adresse.Id_Adresse = utilisateur_adresse.Id_Adresse
                    WHERE utilisateur_adresse.Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des adresses : ' . $e->getMessage());
            return [];
        }
    }

    public function getAnimalById($animalId) {
        try {
            $sql = "SELECT Id_Animal, nom_animal, race_animal FROM animal WHERE Id_Animal = :animalId AND Id_utilisateur = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'animal : ' . $e->getMessage());
            return false;
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
