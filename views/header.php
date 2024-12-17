<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
        <a href="#" class="dropbtn">🐕 PetSitter</a>
        <div class="dropdown-content">
            <a href="/PetBesties/petsitter">Voir les annonces</a>
        </div>
    </div>
    <div class="dropdown">
        <a href="#" class="dropbtn">🐾 PetOwner</a>
        <div class="dropdown-content">
            <a href="/PetBesties/petowner">Voir les annonces</a>
        </div>
    </div>
    <a href="/PetBesties/contact">Contact</a>
  </nav>

  <?php if (isset($_SESSION['user_id'])): ?>
    <div class="dropdown">
        <button class="profile-button">Profil</button>
        <div class="dropdown-content">
            <a href="/PetBesties/profil">Profil</a>
            <a href="/PetBesties/conversation">conversation</a>
            <a href="/PetBesties/candidatures">Mes candidatures</a>
            <a href="/PetBesties/historique">Mon historique</a>
            <a href="/PetBesties/coups_de_coeur">Coups de coeur</a>
            <a href="/PetBesties/contact">Contact</a>
            <a href="/PetBesties/logout">Déconnexion</a>
        </div>
    </div>
  <?php else: ?>
    <a href="/PetBesties/connexion" class="profile-button">Connexion</a>
    <a href="/PetBesties/inscription" class="profile-button">Inscription</a>
  <?php endif; ?>
</header>

</body>
</html>
</body>
</html>