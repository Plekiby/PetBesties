<?php
require_once __DIR__ . '/../models/Annonce.php';
require_once __DIR__ . '/../models/Utilisateur.php'; // Added to use Utilisateur model

class AnnonceController {
    private $model;
    private $utilisateurModel; // Added property

    public function __construct() {
        $this->model = new Annonce();
        $this->utilisateurModel = new Utilisateur(); // Instantiate Utilisateur model
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

    public function showPostAnnonceForm() {
        // Fetch gardiennage options
        require_once __DIR__ . '/../models/Gardiennage.php';
        $gardiennageModel = new Gardiennage();
        $gardiennages = $gardiennageModel->fetchAll();

        // Fetch promenade options
        require_once __DIR__ . '/../models/Promenade.php';
        $promenadeModel = new Promenade();
        $promenades = $promenadeModel->fetchAll();

        // Fetch existing addresses
        require_once __DIR__ . '/../models/Adresse.php';
        $adresseModel = new Adresse();
        $adresses = $adresseModel->fetchAll();

        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            // Fetch user's animals using Utilisateur model
            $animals = $this->utilisateurModel->getUserAnimals($userId);
            // Fetch user's addresses using Utilisateur model
            $adresses = $this->utilisateurModel->getUserAddresses($userId);
        } else {
            // Redirect to login if not authenticated
            header('Location: /PetBesties/connexion');
            exit;
        }

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/poster_annonce.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function postAnnonce() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Start session if not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['user_id'])) {
                // Récupérer et valider les données du formulaire
                $type = $_POST['type_annonce'] ?? null; // Type: garde ou promenade
                $animalId = $_POST['Id_Animal'] ?? null;
                $adresseId = $_POST['Id_Adresse'] ?? null;
                $details = $_POST['details_annonce'] ?? null;

                // Générer le titre basé sur le type et le nom de l'animal
                if ($animalId && $type) {
                    // Récupérer le nom de l'animal
                    $animal = $this->utilisateurModel->getAnimalById($animalId); // Assurez-vous que cette méthode existe
                    if ($animal) {
                        $titre = ucfirst($type) . ' de ' . $animal['nom_animal'];
                    } else {
                        $error = "Animal sélectionné invalide.";
                    }
                } else {
                    $error = "Type d'annonce et animal sont requis.";
                }

                // Vérifier si une nouvelle adresse doit être créée
                if (!$adresseId) {
                    $adresseId = $this->createNewAdresse($_POST);
                    if (!$adresseId) {
                        $error = "Adresse invalide ou incomplète.";
                    }
                }

                // Vérifier que toutes les données nécessaires sont présentes
                if (isset($titre) && isset($details) && isset($adresseId)) {
                    // Appeler le modèle pour créer l'annonce avec les paramètres requis
                    $result = $this->model->createAnnonce(
                        $titre,
                        $details, // Description remplacée par détails
                        date('Y-m-d'), // Date de début actuelle
                        0, // Durée par défaut, ajustez si nécessaire
                        0, // Tarif par défaut, ajustez si nécessaire
                        $_SESSION['user_id'],
                        $type,
                        $details,
                        $adresseId
                    );

                    if ($result) {
                        $success = "Annonce postée avec succès!";
                    } else {
                        $error = "Erreur lors de la publication de l'annonce. Veuillez réessayer.";
                    }
                } else {
                    if (!isset($error)) {
                        $error = "Tous les champs sont obligatoires.";
                    }
                }

                include __DIR__ . '/../views/header.php';
                include __DIR__ . '/../views/poster_annonce.php';
                include __DIR__ . '/../views/footer.php';
            } else {
                header('Location: /PetBesties/connexion');
                exit;
            }
        } else {
            header('Location: /PetBesties/poster_annonce');
            exit;
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
                'complement' => $postData['complement_adresse'],
                'latitude' => $postData['latitude'],
                'longitude' => $postData['longitude']
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

} // Correction de la fermeture de la classe

?>
