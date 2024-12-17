<?php
require_once __DIR__ . '/routeur.php';

$baseUri = '/PetBesties';
$rawUri = $_SERVER['REQUEST_URI']; 
$requestUri = parse_url($rawUri, PHP_URL_PATH); 

if (strpos($requestUri, $baseUri) === 0) {
    $requestUri = substr($requestUri, strlen($baseUri));
}

$requestUri = rtrim($requestUri, '/') ?: '/';

$router->dispatch($requestUri);
?>
