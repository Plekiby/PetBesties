<?php
require_once __DIR__ . '/../models/Annonce.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Animal.php'; // Ajout du modèle Animal

class AnnonceController {
    private $model;
    private $utilisateurModel; // Added property
    private $animalModel;

    public function __construct() {
        $this->model = new Annonce();
        $this->utilisateurModel = new Utilisateur(); // Instantiate Utilisateur model
        $this->animalModel = new Animal();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $annonces = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $annonces;
    }

    public function getAnnoncesByType($type) {
        try {
            // Utiliser la méthode fetchByUserType du modèle
            return $this->model->fetchByUserType($type);
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces par type : ' . $e->getMessage());
            return [];
        }
    }

    public function showPostAnnonceForm($error = null, $success = null) {
        require_once __DIR__ . '/../models/Gardiennage.php';
        $gardiennageModel = new Gardiennage();
        $gardiennages = $gardiennageModel->fetchAll();

        require_once __DIR__ . '/../models/Promenade.php';
        $promenadeModel = new Promenade();
        $promenades = $promenadeModel->fetchAll();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $animals = $this->utilisateurModel->getUserAnimals($userId);
            $adresses = $this->utilisateurModel->getUserAddresses($userId);
        } else {
            header('Location: /PetBesties/connexion');
            exit;
        }

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/poster_annonce.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function postAnnonce() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
    
            if (!isset($_SESSION['user_id'])) {
                header('Location: /PetBesties/connexion');
                exit;
            }
    
            $userId = $_SESSION['user_id'];
            $action = $_POST['action'] ?? '';
    
            if ($action == 'create_animal') {
                // Logique pour créer un animal
            } elseif ($action == 'create_address') {
                // Logique pour créer une adresse
                require_once __DIR__ . '/../models/Adresse.php';
                $adresseModel = new Adresse();
    
                $numero = $_POST['numero_adresse'] ?? null;
                $rue = $_POST['rue_adresse'] ?? null;
                $nom = $_POST['nom_adresse'] ?? null;
                $complement = $_POST['complement_adresse'] ?? null;
                $latitude = $_POST['latitude'] ?? null;
                $longitude = $_POST['longitude'] ?? null;
    
                if ($numero && $rue && $nom) {
                    $adresseData = [
                        'numero' => $numero,
                        'rue' => $rue,
                        'nom' => $nom,
                        'complement' => $complement,
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ];
    
                    $newAdresseId = $adresseModel->create($adresseData);
                    if ($newAdresseId) {
                        // Associer l'adresse à l'utilisateur
                        $this->utilisateurModel->addUserAddress($userId, $newAdresseId);
                        // Une fois créée, on peut recharger la page
                        header('Location: /PetBesties/poster_annonce');
                        exit;
                    } else {
                        $error = "Erreur lors de la création de l'adresse.";
                        $this->showPostAnnonceForm($error);
                    }
                } else {
                    $error = "Veuillez renseigner au moins le numéro, la rue et la ville.";
                    $this->showPostAnnonceForm($error);
                }
    
            } elseif ($action == 'post_annonce') {
                // Logique pour poster une annonce
            } else {
                header('Location: /PetBesties/connexion');
                exit;
            }
        }
    }
    

    public function showAnnonce($id) {
        try {
            // Valider et nettoyer l'ID
            $id = intval($id);
            if ($id <= 0) {
                throw new Exception("ID d'annonce invalide.");
            }

            // Récupérer l'annonce par ID
            $annonce = $this->model->getAnnonceById($id);

            if ($annonce) {
                include __DIR__ . '/../views/header.php';
                include __DIR__ . '/../views/afficher_annonce.php';
                include __DIR__ . '/../views/footer.php';
            } else {
                // Gérer le cas où l'annonce n'existe pas
                http_response_code(404);
                echo "Annonce non trouvée.";
            }
        } catch (Exception $e) {
            error_log('Erreur dans showAnnonce : ' . $e->getMessage());
            http_response_code(500);
            echo "Erreur interne du serveur.";
        }
    }

    private function createNewAdresse($postData) {
        if (!empty($postData['numero_adresse']) && !empty($postData['rue_adresse']) && !empty($postData['nom_adresse'])) {
            require_once __DIR__ . '/../models/Adresse.php';
            $adresseModel = new Adresse();
            $adresseData = [
                'numero' => $postData['numero_adresse'],
                'rue' => $postData['rue_adresse'],
                'nom' => $postData['nom_adresse'],
                'complement' => $postData['complement_adresse'] ?? null,
                'latitude' => $postData['latitude'] ?? null,
                'longitude' => $postData['longitude'] ?? null
            ];
            return $adresseModel->create($adresseData);
        }
        return null;
    }

    public function fetchAll() {
        try {
            // Utiliser la méthode fetchAll du modèle
            return $this->model->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces : ' . $e->getMessage());
            return [];
        }
    }

    // Ajouter une méthode pour récupérer un animal par ID
    public function getAnimalById($animalId) {
        // Supposons que le modèle Utilisateur a une méthode pour cela
        // Sinon, implémentez-la dans le modèle
        $animals = $this->utilisateurModel->getUserAnimals($_SESSION['user_id']);
        foreach ($animals as $animal) {
            if ($animal['Id_Animal'] == $animalId) {
                return $animal;
            }
        }
        return null;
    }

    // Ajouter une méthode pour créer un animal
    public function createAnimal($userId, $nom, $race) {
        return $this->utilisateurModel->createAnimal($userId, $nom, $race);
    }

} // Correction de la fermeture de la classe

?>
