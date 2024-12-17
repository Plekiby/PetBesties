<?php

// Enable error reporting during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// File: routeur.php

class Router {
    private $routes = [];

    public function add($path, $callback) {
        $this->routes[$path] = $callback;
    }

    public function dispatch($requestUri) {
        // Remove base path from URI
        $basePath = '/PetBesties';
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
    
        $normalizedUri = trim($requestUri, '/'); 
    
        foreach ($this->routes as $path => $callback) {
            $normalizedPath = trim($path, '/'); 
    
            if ($normalizedPath === $normalizedUri) {
                return call_user_func($callback);
            }
        }
    
        http_response_code(404);
        echo "404 - Page non trouvée";
    }
}

// Créer une instance du routeur
$router = new Router();

$router->add('/', function() {
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/accueil.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});


$router->add('/petowner', function() {
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    $annonces = $controller->getAnnoncesByType(0); // type_utilisateur = 0 pour PetOwner

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/petOwnerAnnonce.php'; // La vue utilise $annonces
    include __DIR__ . '/views/footer.php';
});

$router->add('/petsitter', function() {
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    $annonces = $controller->getAnnoncesByType(1); // type_utilisateur = 1 pour PetSitter

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/petSitterAnnonce.php'; // Nouvelle vue pour PetSitter
    include __DIR__ . '/views/footer.php';
});

$router->add('/profil', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/page_de_profil.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

$router->add('/contact', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/contact.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

$router->add('/historique', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/monhistorique.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

$router->add('/prestations', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/prestations.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});


$router->add('/candidatures', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header('Location: /PetBesties/connexion');
        exit;
    }

    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    $annonces = $controller->index();

    require_once __DIR__ . '/controllers/PostuleController.php';
    $controllerpostu = new PostuleController();
    $candidatures = $controllerpostu->index($userId);

    require_once __DIR__ . '/controllers/UtilisateurController.php';
    $controlleruti = new UtilisateurController();

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/mescandidatures.php'; // La vue utilise $annonces et $candidatures
    include __DIR__ . '/views/footer.php';
});

$router->add('/coups_de_coeur', function() {
    // Inclure les vues avec les données transmises
    require_once __DIR__ . '/controllers/AimeController.php';
    $controlleraime = new AimeController();
    $favoris = $controlleraime->index();
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/coupsdecoeur.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

$router->add('/inscription', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $data = [
            'prenom' => $_POST['prenom'],
            'nom' => $_POST['nom'],
            'email' => $_POST['email'],
            'mdp' => $_POST['mdp'],
            'telephone' => $_POST['telephone'],
            'type' => 1,
            'rib' => '',
            'adresseId' => 4
        ];
        if ($controller->register($data)) {
            header('Location: /PetBesties/'); // Modifiez cette ligne
            exit;
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/Inscription.php';
    include __DIR__ . '/views/footer.php';
});

// Add separate GET and POST routes for '/connexion'

$router->add('/connexion', function() {
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/connexion.php';
    include __DIR__ . '/views/footer.php';
}, 'GET');

$router->add('/connexion', function() {
    require_once __DIR__ . '/controllers/UtilisateurController.php';
    $controller = new UtilisateurController();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($controller->login($email, $password)) {
        header('Location: /PetBesties/profil');
        exit;
    } else {
        $error = "Email ou mot de passe invalide.";
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/connexion.php';
        include __DIR__ . '/views/footer.php';
    }
}, 'POST');

$router->add('/logout', function() {
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }
    session_destroy();
    header('Location: /PetBesties/');
    exit;
});

?>

