<?php

// File: routeur.php

class Router {
    private $routes = [];

    public function add($path, $callback) {
        $this->routes[$path] = $callback;
    }

    public function dispatch($requestUri) {
        $normalizedUri = trim(rtrim($requestUri, '/')); 
    
        foreach ($this->routes as $path => $callback) {
            $normalizedPath = trim(rtrim($path, '/')); 
    
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
    $annonces = $controller->index();

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/petOwnerAnnonce.php'; // La vue utilise $annonces
    include __DIR__ . '/views/footer.php';
});

$router->add('/profil', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/page_de_profil.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

?>
