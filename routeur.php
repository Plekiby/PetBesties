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
    
            // Convert route path with parameters to a regex pattern
            $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $normalizedPath);
            $routePattern = "@^" . $routePattern . "$@";
    
            if (preg_match($routePattern, $normalizedUri, $matches)) {
                array_shift($matches); // Remove the full match
                return call_user_func_array($callback, $matches);
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


$router->add('/petsitter', function() {
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    
    // Récupérer les paramètres GET
    $filters = [
        'type_annonce' => isset($_GET['type_annonce']) ? $_GET['type_annonce'] : [],
        'prix_max' => isset($_GET['prix_max']) ? (float)$_GET['prix_max'] : 200.00,
        'type_animal' => isset($_GET['type_animal']) ? $_GET['type_animal'] : []
    ];
    
    $annonces = $controller->getAnnoncesByTypeAndFilters(0, $filters); // type_utilisateur = 0 pour PetOwner

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/petSitterAnnonce.php';
    include __DIR__ . '/views/footer.php';
});



$router->add('/petowner', function() {
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controller = new AnnonceController();
    
    // Récupérer les paramètres GET
    $filters = [
        'type_annonce' => isset($_GET['type_annonce']) ? $_GET['type_annonce'] : [],
        'prix_max' => isset($_GET['prix_max']) ? (float)$_GET['prix_max'] : 200.00,
        'type_animal' => isset($_GET['type_animal']) ? $_GET['type_animal'] : []
    ];
    
    $annonces = $controller->getAnnoncesByTypeAndFilters(1, $filters); // type_utilisateur = 0 pour PetOwner

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/petOwnerAnnonce.php';
    include __DIR__ . '/views/footer.php';
});



$router->add('/profil', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        
        // Inclure les contrôleurs nécessaires
        require_once __DIR__ . '/controllers/UtilisateurController.php';
        require_once __DIR__ . '/controllers/AnimalController.php';
        require_once __DIR__ . '/controllers/AnnonceController.php';
        
        // Instancier les contrôleurs
        $utilisateurController = new UtilisateurController();
        $animalController = new AnimalController();
        $annonceController = new AnnonceController();
        
        // Récupérer les données de l'utilisateur
        $utilisateur = $utilisateurController->fetchOne($userId);
        
        // Récupérer les animaux de l'utilisateur
        $animaux = $animalController->fetchAnimals($userId);
        
        // Récupérer les annonces de l'utilisateur
        $annonces = $annonceController->fetchAnnoncesByUser($userId);
        
        // Inclure les vues avec les données transmises
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/page_de_profil.php';
        include __DIR__ . '/views/footer.php';
    } else {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header('Location: /PetBesties/connexion');
        exit;
    }
});


$router->add('/contact', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/contact.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

$router->add('/prestations', function() {
    // Démarrer la session si ce n'est pas déjà fait
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: /PetBesties/connexion');
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Inclure le contrôleur AnnonceController
    require_once __DIR__ . '/controllers/AnnonceController.php';
    $controllerann = new AnnonceController();

    // Utiliser la méthode publique pour récupérer les données de l'utilisateur
    $utilisateur = $controllerann->getUserData($userId);
    if ($utilisateur) {
        $nom_utilisateur = $utilisateur['nom_utilisateur'];
    } else {
        // Si l'utilisateur n'est pas trouvé, rediriger ou gérer l'erreur
        header('Location: /PetBesties/connexion');
        exit;
    }

    // Récupérer les annonces (ajustez selon vos besoins)
    $annonces = $controllerann->fetchAll(); // Ou une méthode spécifique comme getAnnoncesByUser($userId)

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/prestations.php'; 
    include __DIR__ . '/views/footer.php';
});



$router->add('/candidatures', function() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: /PetBesties/connexion');
        exit;
    }

    $userId = $_SESSION['user_id'];

    require_once __DIR__ . '/controllers/PostuleController.php';
    $controller = new PostuleController();
    $candidatures = $controller->index($userId);

    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/mescandidatures.php';
    include __DIR__ . '/views/footer.php';
});

$router->add('/coups_de_coeur', function() {
    // Inclure les vues avec les données transmises
    require_once __DIR__ . '/controllers/AimeController.php';
    $controlleraime = new AimeController();
    $favoris = $controlleraime->index();
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/coupsdecoeur.php'; 
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

// Route pour afficher le profil public d'un utilisateur spécifique
$router->add('/profil/{id}', function($id) {
    require_once __DIR__ . '/controllers/PublicProfileController.php';
    $controller = new PublicProfileController();
    $controller->showProfile($id);
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


$router->add('/historique', function() {
session_start(); // Démarre la session pour récupérer l'ID utilisateur
$userId = $_SESSION['user_id'] ?? 1;
    
require_once __DIR__ . '/controllers/HistoriqueController.php';
$controller = new HistoriqueController();
$historique = $controller->index($userId);
    
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/monhistorique.php';
        include __DIR__ . '/views/footer.php';
});
    

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

$router->add('/postuler', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "Route POST /postuler atteinte.<br>";
        error_log("Route POST /postuler atteinte.");

        session_start();
        if (!isset($_SESSION['user_id'])) {
            error_log("Utilisateur non connecté.");
            header('Location: /PetBesties/connexion');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $annonceId = intval($_POST['annonce_id'] ?? 0);

        echo "User ID: " . htmlspecialchars($userId) . "<br>";
        echo "Annonce ID: " . htmlspecialchars($annonceId) . "<br>";
        error_log("User ID: " . $userId);
        error_log("Annonce ID: " . $annonceId);

        if ($annonceId <= 0) {
            error_log("ID d'annonce invalide : " . $annonceId);
            header('Location: /PetBesties/profil?error=Annonce+invalide');
            exit;
        }

        // Inclure le contrôleur des candidatures
        require_once __DIR__ . '/controllers/CandidatureController.php';
        $controller = new CandidatureController();
        $result = $controller->postuler($userId, $annonceId);

        if ($result) {
            // Récupérer l'ID du propriétaire de l'annonce
            require_once __DIR__ . '/controllers/AnnonceController.php';
            $annonceController = new AnnonceController();
            $annonce = $annonceController->fetchOne($annonceId);

            if ($annonce && isset($annonce['Id_utilisateur'])) {
                $annonceOwnerId = $annonce['Id_utilisateur'];
                error_log("Postulation réussie vers le propriétaire de l'annonce ID : " . $annonceOwnerId);
                header('Location: /PetBesties/profil/' . $annonceOwnerId . '?success=Postulation+réussie');
                exit;
            } else {
                error_log("Annonce ou propriétaire non trouvé pour l'ID : " . $annonceId);
                header('Location: /PetBesties/profil?success=Postulation+réussie');
                exit;
            }
        } else {
            error_log("Erreur lors de la postulation pour l'annonce ID : " . $annonceId);
            header('Location: /PetBesties/profil?error=Erreur+de+postulation');
            exit;
        }
    } else {
        http_response_code(405);
        echo "Méthode non autorisée.";
    }
}, 'POST');



$router->add('/postuler', function() {
    echo "Cette page est destinée à recevoir des candidatures via une requête POST.";
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
        $newId = $controllerAnimal->addAnimal($userId, $nomAnimal, $raceAnimal, $ageAnimal, $infoAnimal);
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

