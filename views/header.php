<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetBesties</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }
        header {
            background-color: #009688;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: #00796b;
            padding: 10px;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <h1>PetBesties</h1>
</header>
<nav>
    <a href="/public/index.php">Accueil</a>
    <a href="prestataireList.php">Trouver PetSitter</a>
    <a href="#">Devenir PetOwner</a>
    <a href="page_de_profil.php">Profil</a>
    <a href="#">S'inscrire</a>
    <a href="#">Connexion</a>
</nav>
