<!DOCTYPE html>
<html>
<head>
    <title>Mes coups de cœur</title>
    <link rel="stylesheet" type="text/css" href="/PetBesties/public/css/coupsdecoeur.css">
</head>
<body>
    <h1 class="titre">Mes coups de cœur</h1>
        <?php if (!empty($favoris)): ?>
            <div class="premier_carre">
                <?php foreach ($favoris as $favori): ?>
                    <?php if ($favori['favoris'] == '1'): ?>
                    <div class="sous-carre">
                        <div class="profil">
                            <span class="initiales">
                                <?= strtoupper($favori['prenom_utilisateur'][0] . $favori['nom_utilisateur'][0]); ?>
                            </span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($favori['titre_annonce']); ?><br><?= htmlspecialchars($favori['datePublication_annonce']); ?></h4>
                            <h6><?= htmlspecialchars($favori['prenom_utilisateur']); ?> <?= htmlspecialchars($favori['nom_utilisateur']); ?></h6>  
                            <h6><?= htmlspecialchars($favori['date_favoris']); ?></h6> 
                        </div>
                        <button class="coeur">❤</button>
                    </div>
                    <?php else: ?>
                        <p>Aucun favori trouvé </p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun favori trouvé</p>
        <?php endif; ?>
</body>
</html>
