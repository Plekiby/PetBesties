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
        // Appeler le modèle pour récupérer les données filtrées
        $annonces = $this->model->fetchByUserType($type);

        // Retourner les données pour la vue
        return $annonces;
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
                $titre = $_POST['titre'] ?? '';
                $description = $_POST['description'] ?? '';
                $dateDebut = $_POST['dateDebut'] ?? '';
                $duree = $_POST['duree'] ?? '';
                $tarif = $_POST['tarif'] ?? '';
                $gardiennage = $_POST['gardiennage'] ?? '';
                $promenade = $_POST['promenade'] ?? '';
                $adresseId = $_POST['adresse'] ?? '';
                $id_utilisateur = $_SESSION['user_id'];

                // Validate input fields
                if (empty($titre) || empty($description) || empty($dateDebut) || empty($duree) || empty($tarif) || empty($gardiennage) || empty($promenade) || empty($adresseId)) {
                    $error = "Tous les champs sont obligatoires.";
                } else {
                    $result = $this->model->createAnnonce($titre, $description, $dateDebut, $duree, $tarif, $id_utilisateur, $gardiennage, $promenade, $adresseId);

                    if ($result === true) {
                        $success = "Annonce postée avec succès!";
                    } else {
                        // Log the specific error returned from the model
                        error_log("Erreur lors de la publication de l'annonce pour l'utilisateur ID {$id_utilisateur}: {$result}");
                        $error = "Erreur lors de la publication de l'annonce. Veuillez réessayer.";
                        // Pass the detailed error to the view (optional, for development)
                        $detailed_error = $result;
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

}
?>
