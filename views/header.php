<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PetBesties</title>
	<link rel="stylesheet" href="/petbesties/public/css/styleheader.css">   
</head>
<body>
<header class="header">
  <div class="logo">
    <img src="/petbesties/public/images/logoPB.jpg">
  </div>

  <nav class="navigation">
    <div class="dropdown">
      <a href="#">🐕 PetSitter</a>
      <div class="dropdown-menu">
        <a href="#">Poster une annonce</a>
        <a href="#">Postuler à une annonce</a>
      </div>
    </div>
    <div class="dropdown">
      <a href="#">🐾 PetOwner</a>
      <div class="dropdown-menu">
        <a href="#">Poster une annonce</a>
        <a href="#">Postuler à une annonce</a>
      </div>
   
  </nav>

    </div>
  </div>
  <?php if (isset($_SESSION['user_id'])): ?>
    <div class="auth-buttons">
        <a href="/PetBesties/connexion" class="profile-button">Profil</a>
        <a href="/PetBesties/conversation" class="profile-button">Conversations</a>
        <div class="dropdown-menu">
            <a href="/PetBesties/candidatures">Mes candidatures</a>
            <a href="/PetBesties/historique">Mon historique</a>
            <a href="/PetBesties/coups_de_coeur">Mes coups de coeur</a>
            <a href="/PetBesties/contact">Contact</a>
            <a href="/PetBesties/logout">Déconnexion</a>

        </div>
    </div>
<?php else: ?>
    <div class="auth-buttons">
        <a href="/PetBesties/connexion" class="profile-button">Connexion</a>
        <a href="/PetBesties/inscription" class="profile-button">Inscription</a>
        
        
    </div>
<?php endif; ?>

</header>

</body>
</html>



