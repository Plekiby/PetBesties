<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="/Petbesties/public/css/style_Inscription.css">
</head>

<body>

    <h1 class="title">Inscription</h1>
    <div class="container">
      <form class="form" method="POST" action="/PetBesties/inscription">
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="email" name="email" placeholder="Adresse mail" required>
        <input type="password" name="mdp" placeholder="Créer un mot de passe" required>
        <input type="text" name="adresse_numero" placeholder="Numéro" required>
        <input type="text" name="adresse_rue" placeholder="Rue" required>
        <input type="text" name="adresse_nom" placeholder="Ville" required>
        <input type="text" name="adresse_complement" placeholder="Complément">
        <button type="submit">NEXT</button>
      </form>
      <p class="footer-text">
        En me connectant ou en m'inscrivant, j'accepte les 
        <a href="#">Conditions Générales de Service</a> et la 
        <a href="#">Politique de Confidentialité</a> de PetBesties. 
        Je consens à recevoir des e-mails et des communications marketing 
        de la part de PetBesties et de ses affiliés et je confirme avoir 
        18 ans ou plus.
      </p>
    </div>
</body>
</html>

