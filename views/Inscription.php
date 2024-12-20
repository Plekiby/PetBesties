<link rel="stylesheet" href="/Petbesties/public/css/style_Inscription.css">
<div class="container">
</div>
<div class="container">
</div>
<h1 class="title">Inscription</h1>
<div class="container">
  <form class="form" method="POST" action="/PetBesties/inscription">
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="telephone" placeholder="Téléphone" required>
    <input type="email" name="email" placeholder="Adresse mail" required>
    <input type="password" name="mdp" placeholder="Créer un mot de passe" required>
    <input type="hidden" name="age" value="0">
    <input type="hidden" name="code_postal" value="00000">
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
