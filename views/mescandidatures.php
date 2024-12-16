<!DOCTYPE html>
<html>
<head>
    <title>Mes candidatures</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/mescandidatures.css">
</head>
<body>
    <h1 class="titre">Mes candidatures</h1>
    <div class="container">
        <?php if (!empty($annonces)): ?>
            <div class="premier_carre">
                <h3 class="sous-titre">Candidatures envoyées</h3>
                    <?php foreach ($candidatures as $candidature): ?>
                            <div class="sous-carre">
                                <div class="profil1">
                                    <span class="initiales">EA</span>
                                </div>
                                <div class="details">
                                    <h4><?= htmlspecialchars($candidature['titre_annonce']); ?><br><?= htmlspecialchars($candidature['datePublication_annonce']); ?></h4>
                                    <h6><?= htmlspecialchars($candidature['prenom_utilisateur']); ?> <?= htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                                </div>
                            </div>
                    <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune candidature trouvée.</p>
        <?php endif; ?>
            
        <?php if (!empty($annonces)): ?>
            <div class="premier_carre">
                <h3 class="sous-titre">Candidatures reçues</h3>
                    <?php foreach ($candidatures as $candidature): ?>
                        <?php if ($candidature[Id_utilisateur]!=1 && ): ?>

                            <div class="sous-carre">
                                <div class="profil1">
                                    <span class="initiales">EA</span>
                                </div>
                                <div class="details">
                                    <h4><?= htmlspecialchars($candidature['titre_annonce']); ?><br><?= htmlspecialchars($candidature['datePublication_annonce']); ?></h4>
                                    <h6><?= htmlspecialchars($candidature['prenom_utilisateur']); ?> <?= htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>Aucune candidature trouvée.</p>
                        <?php endif; ?>
                    <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune candidature trouvée.</p>
        <?php endif; ?>
    </div>   
</body>
<?php include __DIR__ . '/footer.php'; ?>

</html>