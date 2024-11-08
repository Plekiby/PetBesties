<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetBesties Profile</title>
    <link rel="stylesheet" href="css/page_de_profil.css">
</head>
<?php include __DIR__ . '/header.php'; ?>

<body>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-image">
            <!-- Image de profil (ajoutez votre propre image ici) -->
            <img src="images/image_profil.png" alt="Profile Image">
            <button class="add-image">+ Add an Image</button>
        </div>
        <div class="profile-name">
            <h1>First name</h1>
            <h2>Last name</h2>
        </div>
        <div class="profile-type">
            <button class="active">Pet Sitter</button>
            <button>Pet Owner</button>
        </div>
    </div>

    <div class="profile-description">
    <p>je suis un pet sitter passionné, prêt à prendre soin de vos animaux de compagnie avec attention et affection. Votre compagnon sera entre de bonnes mains !</p>
    <button class="submit">Soumettre</button>
    </div>

    <div class="date-picker">
        <label for="date">Enter date</label>
        <input type="date" id="date">
    </div>

    <div class="annonces">
        <h3>Dernières annonces</h3>
        <div class="annonce">
            <div class="annonce-header">
                <img src="images/Value.png" alt="Stars">
                <p>Pet Sitter Pro</p>
            </div>
            <p>USER 123</p>
            <p>il y a 2 jours</p>
        </div>
        <div class="annonce">
            <div class="annonce-header">
                <img src="images/Value.png" alt="Stars">
                <p>Câlins Garantis</p>
            </div>
            <p>USER 456</p>
            <p>il y a 2 jours</p>
        </div>
        <div class="annonce">
            <div class="annonce-header">
                <img src="images/Value.png" alt="Stars">
                <p>Compagnie Fidèle</p>
            </div>
            <p>USER 789</p>
            <p>il y a 2 jours</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
