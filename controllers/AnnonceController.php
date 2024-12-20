<?php
require_once __DIR__ . '/../models/Annonce.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Animal.php'; // Ajout du modèle Animal
require_once __DIR__ . '/../models/Adresse.php'; // Ajout du modèle Adresse

class AnnonceController {
    private $model;
    private $utilisateurModel; // Added property
    private $animalModel;
    private $adresseModel;
    private $conn;

    public function __construct() {
        $this->model = new Annonce();
        $this->utilisateurModel = new Utilisateur(); // Instantiate Utilisateur model
        $this->animalModel = new Animal();
        $this->adresseModel = new Adresse();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function index() {
        // Appeler le modèle pour récupérer les données
        $annonces = $this->model->fetchAll();

        // Retourner les données pour la vue
        return $annonces;
    }

    public function getAnnoncesByTypeAndFilters($type_utilisateur, $filters) {
        return $this->model->fetchFilteredAnnonces($type_utilisateur, $filters);
    }

    // Méthode publique pour accéder aux données de l'utilisateur
    public function getUserData($userId) {
        return $this->utilisateurModel->fetchOne($userId);
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

    /**
     * Récupère une annonce spécifique par son ID.
     *
     * @param int $annonceId L'ID de l'annonce à récupérer.
     * @return array|false Les données de l'annonce ou false en cas d'erreur.
     */
    public function fetchOne($annonceId) {
        return $this->model->fetchOne($annonceId);
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
                $nomAnimal = trim($_POST['nom_animal'] ?? '');
                $raceAnimal = trim($_POST['race_animal'] ?? '');
                $ageAnimal = intval($_POST['age_animal'] ?? 0);
                $infoAnimal = trim($_POST['info_animal'] ?? '');

                if (!empty($nomAnimal) && !empty($raceAnimal) && $ageAnimal >= 0 && !empty($infoAnimal)) {
                    $result = $this->animalModel->createAnimal($userId, $nomAnimal, $raceAnimal, $ageAnimal, $infoAnimal);
                    if ($result) {
                        $success = "Animal créé avec succès !";
                    } else {
                        $error = "Erreur lors de la création de l'animal.";
                    }
                } else {
                    $error = "Veuillez remplir tous les champs pour l'animal.";
                }

            } elseif ($action == 'create_address') {
                // Logique pour créer une adresse
                $numero = trim($_POST['numero_adresse'] ?? '');
                $rue = trim($_POST['rue_adresse'] ?? '');
                $ville = trim($_POST['nom_adresse'] ?? '');
                $complement = trim($_POST['complement_adresse'] ?? '');
                $latitude = trim($_POST['latitude'] ?? null);
                $longitude = trim($_POST['longitude'] ?? null);

                if (!empty($numero) && !empty($rue) && !empty($ville)) {
                    $adresseData = [
                        'numero' => $numero,
                        'rue' => $rue,
                        'nom' => $ville,
                        'complement' => $complement,
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ];

                    $newAdresseId = $this->adresseModel->create($adresseData);
                    if ($newAdresseId) {
                        // Associer l'adresse à l'utilisateur
                        $this->utilisateurModel->addUserAddress($userId, $newAdresseId);
                        $success = "Adresse créée avec succès !";
                    } else {
                        $error = "Erreur lors de la création de l'adresse.";
                    }
                } else {
                    $error = "Veuillez remplir les champs obligatoires de l'adresse.";
                }

            } elseif ($action === 'post_annonce') {
                // Logique pour poster une annonce

                // Récupérer les données du formulaire
                $type_annonce = $_POST['type_annonce'] ?? '';
                $Id_Animal = intval($_POST['Id_Animal'] ?? 0);
                $Id_Adresse = intval($_POST['Id_Adresse'] ?? 0);
                $details_annonce = trim($_POST['details_annonce'] ?? '');
                $price_annonce = floatval($_POST['price_annonce'] ?? 0.0);
                $date_debut_annonce = $_POST['date_debut_annonce'] ?? '';
                $duree_annonce = intval($_POST['duree_annonce'] ?? 0);

                // Valider les données
                if (empty($type_annonce) || empty($Id_Animal) || empty($Id_Adresse) || empty($details_annonce) || $price_annonce < 0 || empty($date_debut_annonce) || $duree_annonce <= 0) {
                    $error = "Veuillez remplir tous les champs de l'annonce.";
                    error_log("Validation échouée pour l'annonce : champs manquants ou invalides.");
                } else {
                    // Valider que type_annonce est 'gardiennage' ou 'promenade'
                    if (!in_array($type_annonce, ['gardiennage', 'promenade'], true)) {
                        $error = "Type d'annonce invalide.";
                        error_log("Type_annonce invalide : {$type_annonce}");
                    } 
                    // Fetch the animal's details
                    $animal = $this->animalModel->getAnimalById($Id_Animal, $userId);
                    if (!$animal) {
                        $error = "Animal sélectionné invalide.";
                    } else {
                        $animalName = $animal['nom_animal'];

                        // Générer le titre
                        if ($type_annonce === 'gardiennage') {
                            $typeAnnonceText = 'Garde';
                        } else {
                            $typeAnnonceText = 'Promenade';
                        }
                        $titre_annonce = "{$typeAnnonceText} de " . ucfirst(strtolower($animalName));

                        // Préparer les données pour l'insertion
                        $description_annonce = $details_annonce;
                        $dateDebut_annonce = date('Y-m-d', strtotime($date_debut_annonce)); // Assurez-vous du format
                        $duree_annonce = $duree_annonce; // Déjà converti en entier
                        $tarif_annonce = $price_annonce; // Déjà converti en float
                        $typeAnnonce = ($type_annonce === 'gardiennage') ? 1 : 0; // 1 pour Gardiennage, 0 pour Promenade

                        // Appeler le modèle pour créer l'annonce
                        error_log("Tentative de création d'une annonce de type {$typeAnnonce}.");

                        $result = $this->model->createAnnonce(
                            $titre_annonce,
                            $description_annonce,
                            $dateDebut_annonce,
                            $duree_annonce,
                            $tarif_annonce,
                            $userId,
                            $typeAnnonce,
                            $details_annonce,
                            $Id_Adresse,
                            $Id_Animal
                        );

                        if ($result) {
                            $success = "Annonce créée avec succès !";
                            error_log("Annonce créée avec l'ID {$result}.");
                        } else {
                            $error = "Erreur lors de la création de l'annonce.";
                            error_log("Erreur lors de la création de l'annonce pour l'utilisateur ID {$userId}.");
                        }
                    }
                }
            } else {
                $error = "Action non reconnue.";
            }

            // Recharger les données pour le formulaire
            $animals = $this->utilisateurModel->getUserAnimals($userId);
            $adresses = $this->utilisateurModel->getUserAddresses($userId);

            include __DIR__ . '/../views/header.php';
            include __DIR__ . '/../views/poster_annonce.php'; 
            include __DIR__ . '/../views/footer.php';
            exit; // Terminer le script après l'affichage
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

        public function fetchAnnoncesByUser($userId) {
            try {
                $sql = "SELECT 
                            a.*, 
                            u.prenom_utilisateur, 
                            u.nom_utilisateur, 
                            ad.latitude, 
                            ad.longitude,
                            an.nom_animal, 
                            an.race_animal
                        FROM annonce a
                        JOIN utilisateur u ON a.Id_utilisateur = u.Id_utilisateur
                        JOIN adresse ad ON a.Id_Adresse = ad.Id_Adresse
                        LEFT JOIN animal an ON a.Id_Animal = an.Id_Animal
                        WHERE a.Id_utilisateur = :user_id
                        ORDER BY a.datePublication_annonce DESC";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log('Erreur lors de la récupération des annonces de l\'utilisateur : ' . $e->getMessage());
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
