<?php

// Enable error reporting during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// File: routeur.php

class Router {
    private $routes = [];

    public function add($path, $callback, $method = 'GET') {
        $this->routes[$method][$path] = $callback;
    }

    public function dispatch($requestUri) {
        // Remove base path from URI
        $basePath = '/PetBesties';
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
    
        $normalizedUri = trim($requestUri, '/'); 
        $requestMethod = $_SERVER['REQUEST_METHOD'];
    
        foreach ($this->routes[$requestMethod] as $path => $callback) {
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
    include __DIR__ . '/views/petSitterAnnonce.php'; // La vue utilise $annonces
    include __DIR__ . '/views/footer.php';
});

// fct get values users momo 
$router->add('/profil', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        require_once __DIR__ . '/controllers/UtilisateurController.php';
        $controlleruti = new UtilisateurController();
        $utilisateur = $controlleruti->fetchOne($userId);

        // Inclure les vues avec les données transmises
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/page_de_profil.php'; // La vue utilise $prestataires
        include __DIR__ . '/views/footer.php';
    } else {
        exit;
    }
    
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

// Route GET pour afficher le formulaire d'inscription
$router->add('/inscription', function() {
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/Inscription.php';
    include __DIR__ . '/views/footer.php';
}, 'GET');

// Route POST pour traiter la soumission du formulaire d'inscription
$router->add('/inscription', function() {
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
        'age' => isset($_POST['age']) ? $_POST['age'] : 0,
        'code_postal' => isset($_POST['code_postal']) ? $_POST['code_postal'] : '00000'
    ];

    if ($controller->register($data)) {
        header('Location: /PetBesties/');
        exit;
    } else {
        $error = "Erreur lors de l'inscription.";
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/Inscription.php';
        include __DIR__ . '/views/footer.php';
    }
}, 'POST');

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

//////////////////////////////////
// Partie API
//////////////////////////////////

$router->add('/api/user-data', function() {
    session_start();
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["error" => "Utilisateur non connecté"]);
        exit;
    }

    require_once __DIR__ . '/controllers/UtilisateurController.php';
    $controller = new UtilisateurController();
    $utilisateur = $controller->fetchOne($_SESSION['user_id']);

    if ($utilisateur) {
        // Adapter les noms de champs selon la base de données
        $data = [
            "prenom" => $utilisateur['prenom_utilisateur'],
            "nom" => $utilisateur['nom_utilisateur'],
            "email" => $utilisateur['email_utilisateur'],
            "telephone" => $utilisateur['telephone_utilisateur']
        ];
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Utilisateur non trouvé"]);
    }
});

$router->add('/api/update-user', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        require_once __DIR__ . '/controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $result = $controller->updateProfile($_SESSION['user_id'], $_POST);
        
        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Mise à jour échouée."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
    }
});

// Route GET pour afficher le formulaire de "poster_annonce"
$router->add('/poster_annonce', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: /PetBesties/connexion');
        exit;
    }

    require_once __DIR__ . '/controllers/AnimalController.php';
    require_once __DIR__ . '/controllers/AdresseController.php';
    // require_once __DIR__ . '/controllers/AnnonceController.php'; // seulement si besoin

    $animalController = new AnimalController();
    $adresseController = new AdresseController();

    // Récupérer les données nécessaires pour le formulaire
    $animals = $animalController->fetchAnimals($_SESSION['user_id']);
    $adresses = $adresseController->fetchAdressesByUser($_SESSION['user_id']);

    $error = null;
    $success = null;

    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/poster_annonce.php'; 
    include __DIR__ . '/views/footer.php';
}, 'GET');

// Route POST pour traiter les formulaires de "poster_annonce"
$router->add('/poster_annonce', function() {
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    $controller->postAnnonce();
}, 'POST');



// Route pour afficher une annonce spécifique après sa création
$router->add('/annonce/{id}', function($id) {
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    $controller->showAnnonce($id);
});

$router->add('/ajouter_animal', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: /PetBesties/connexion');
        exit;
    }

    // Afficher le formulaire
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/ajouter_animal.php';
    include __DIR__ . '/views/footer.php';
}, 'GET');

$router->add('/ajouter_animal', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: /PetBesties/connexion');
        exit;
    }

    require_once __DIR__ . '/controllers/AnimalController.php';
    $controllerAnimal = new AnimalController();

    $nomAnimal = $_POST['nom_animal'] ?? '';
    $raceAnimal = $_POST['race_animal'] ?? '';
    $userId = $_SESSION['user_id'];

    if (!empty($nomAnimal) && !empty($raceAnimal)) {
        $newId = $controllerAnimal->addAnimal($userId, $nomAnimal, $raceAnimal);
        if ($newId) {
            // Redirection vers le profil
            header('Location: /PetBesties/profil');
            exit;
        } else {
            echo "Erreur lors de la création de l'animal.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}, 'POST');



