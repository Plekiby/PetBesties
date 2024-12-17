<?php
require_once __DIR__ . '/../models/Annonce.php';

class AnnonceController {
    private $model;

    public function __construct() {
        $this->model = new Annonce();
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
                $titre = $_POST['titre_annonce'] ?? null;
                $description = $_POST['description_annonce'] ?? null;
                $dateDebut = $_POST['dateDebut_annonce'] ?? null;
                $duree = $_POST['duree_annonce'] ?? null;
                $tarif = $_POST['tarif_annonce'] ?? null;
                $type = $_POST['type_annonce'] ?? null; // Nouveau champ pour le type
                $details = $_POST['details_annonce'] ?? null; // Nouveau champ pour les détails
                $adresseId = $_POST['Id_Adresse'] ?? null;

                // Vérifier si une nouvelle adresse doit être créée
                if (empty($adresseId)) {
                    $adresseId = $this->createNewAdresse($_POST);
                }

                // Vérifier que toutes les données nécessaires sont présentes
                if ($titre && $description && $dateDebut && $duree && $tarif && $type && $details && $adresseId) {
                    // Appeler le modèle pour créer l'annonce avec les paramètres requis
                    $result = $this->model->createAnnonce(
                        $titre,
                        $description,
                        $dateDebut,
                        $duree,
                        $tarif,
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
                    $error = "Tous les champs sont obligatoires.";
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
    } // Correction de la fermeture de la méthode postAnnonce

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

} // Correction de la fermeture de la classe

?>
