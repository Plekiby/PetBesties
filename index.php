<?php
require_once __DIR__ . '/routeur.php';

// Définir le préfixe de l'application
$baseUri = '/petbesties';
$rawUri = $_SERVER['REQUEST_URI']; // URI brut
$requestUri = parse_url($rawUri, PHP_URL_PATH); // Extraction de l'URI sans les paramètres

// Vérifier si l'URI commence par le préfixe
if (strpos($requestUri, $baseUri) === 0) {
    $requestUri = substr($requestUri, strlen($baseUri));
}

// Normaliser le chemin : s'assurer que l'URI est '/' si vide
$requestUri = rtrim($requestUri, '/') ?: '/';

// Transmettre au routeur
$router->dispatch($requestUri);
?>
