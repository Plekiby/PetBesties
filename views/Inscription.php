<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <!-- Inclusion du CSS spécifique à la page -->
  <link rel="stylesheet" href="/Petbesties/public/css/style_Inscription.css">
</head>
<body>
  <div class="page section-commun">
    <h1 class="title titre-principal">Inscription</h1>
    <div class="container section-commun">
      <!-- Formulaire d'inscription -->
      <form class="form formulaire-commun" method="POST" action="inscription.php">
        <input type="text" name="prenom_utilisateur" placeholder="Prénom" required>
        <input type="text" name="nom_utilisateur" placeholder="Nom" required>
        <input type="email" name="email_utilisateur" placeholder="Adresse email" required>
        <input type="password" name="mdp_utilisateur" placeholder="Mot de passe" required>
        <input type="text" name="telephone_utilisateur" placeholder="Téléphone" optional>
        
        <!-- Choix du statut -->
        <div class="choix-statut">
          <label class="statut-option">
            <input type="radio" name="type_utilisateur" value="PetSitter" required>
            <span class="bouton-statut">PetSitter</span>
          </label>
          <label class="statut-option">
            <input type="radio" name="type_utilisateur" value="PetOwner" required>
            <span class="bouton-statut">PetOwner</span>
          </label>
        </div>
        
        <input type="number" name="age" placeholder="Âge" required>
        <input type="text" name="code_postal" placeholder="Code postal" required>
        <button type="submit" class="bouton-commun">NEXT</button>
      </form>

      <p class="footer-text paragraphe-commun">
        En me connectant ou en m'inscrivant, j'accepte les 
        <a href="#" class="lien-commun">Conditions Générales de Service</a> et la 
        <a href="#" class="lien-commun">Politique de Confidentialité</a> de PetBesties. 
        Je consens à recevoir des e-mails et des communications marketing 
        de la part de PetBesties et de ses affiliés et je confirme avoir 
        18 ans ou plus.
      </p>
    </div>
  </div>
</body>
</html>
