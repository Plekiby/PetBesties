<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateurController {
    private $model;

    public function __construct() {
        $this->model = new Utilisateur();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $utilisateurs = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $utilisateurs;
    }

    public function selectOne($id) {
        // Appeler le modèle pour récupérer les données
        $utilisateur = $this->model->selectOne($id);

        // Retourner les données pour l'API
        return $utilisateur;
    }

    public function fetchOne($id) {
        // Appeler le modèle pour récupérer les données
        $utilisateur = $this->model->selectOne($id);

        // Retourner les données pour la vue
        return $utilisateur;
    }

    public function register($data) {
        // Enable error reporting during development
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Validate required fields
        if (empty($data['prenom']) || empty($data['nom']) || empty($data['email']) || empty($data['mdp']) || empty($data['telephone'])) {
            echo "Tous les champs sont obligatoires.";
            return false;
        }

        $prenom = $data['prenom'];
        $nom = $data['nom'];
        $email = $data['email'];
        $mdp = password_hash($data['mdp'], PASSWORD_BCRYPT);
        $telephone = $data['telephone'];
        $type = $data['type'];
        $rib = $data['rib'];
        $adresseId = $data['adresseId'];

        // Start transaction
        $this->model->beginTransaction();

        try {
            // Insert new address and get Id_Adresse
            require_once __DIR__ . '/../models/Adresse.php';
            $adresseData = [
                'numero' => $data['adresse_numero'],
                'rue' => $data['adresse_rue'],
                'nom' => $data['adresse_nom'],
                'complement' => $data['adresse_complement'],
                'latitude' => null, // Set as needed
                'longitude' => null // Set as needed
            ];
            $adresseModel = new Adresse();
            $adresseId = $adresseModel->create($adresseData);

            // Continue with user creation using the new $adresseId
            $userId = $this->model->create($prenom, $nom, $email, $mdp, $telephone, $type, $rib, $adresseId);

            // Commit transaction
            $this->model->commit();

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_email'] = $email;
                return true;
            } else {
                // Ne pas utiliser 'echo' ici si vous prévoyez une redirection après
                return false;
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->model->rollBack();
            echo 'Erreur lors de l\'inscription : ' . $e->getMessage();
            return false;
        }
    }

    // Ensure the login method correctly authenticates users
    public function login($email, $password) {
        // Delegate the login process to the Utilisateur model
        $user = $this->model->login($email, $password);
        if ($user) {
            // Start session if not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['Id_utilisateur'];
            $_SESSION['user_email'] = $user['email_utilisateur'];
            return true;
        } else {
            return false;
        }
    }

    public function updateProfile($id, $data) {
        // Valider les données nécessaires
        if (empty($data['prenom']) || empty($data['nom']) || empty($data['email']) || empty($data['telephone'])) {
            return false;
        }

        // Nettoyer les données
        $prenom = htmlspecialchars(strip_tags($data['prenom']));
        $nom = htmlspecialchars(strip_tags($data['nom']));
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $telephone = htmlspecialchars(strip_tags($data['telephone']));

        // Mettre à jour les informations utilisateur
        return $this->model->update($id, $prenom, $nom, $email, $telephone);
    }
}
?>