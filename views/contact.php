<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <link rel="stylesheet" href="/Petbesties/public/css/style_contact.css">
</head>
<body>
    <div class="container">
        <h1>Contactez-nous</h1>
        <form class="contact-form">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="...">
            
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="...">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="...">
            
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="..."></textarea>
            
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>