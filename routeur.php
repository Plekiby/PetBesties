<?php

// File: routeur.php

class Router {
    private $routes = [];

    // Ajouter une route avec un chemin et un callback
    public function add($path, $callback) {
        $this->routes[$path] = $callback;
    }

    public function dispatch($requestUri) {
        // Normaliser l'URI à comparer
        $normalizedUri = trim(rtrim($requestUri, '/')); // Supprime les espaces et le slash final
    
        foreach ($this->routes as $path => $callback) {
            $normalizedPath = trim(rtrim($path, '/')); // Normalisation similaire des routes
    
            if ($normalizedPath === $normalizedUri) {
                return call_user_func($callback);
            }
        }
    
        // Gestion par défaut des erreurs 404
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


$router->add('/prestataires', function() {
    // Inclure le contrôleur
    require_once __DIR__ . '/controllers/PrestataireController.php';

    // Instancier le contrôleur et récupérer les données
    $controller = new PrestataireController();
    $prestataires = $controller->index();

    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/prestataireList.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

$router->add('/profil', function() {
    // Inclure les vues avec les données transmises
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/page_de_profil.php'; // La vue utilise $prestataires
    include __DIR__ . '/views/footer.php';
});

?>
