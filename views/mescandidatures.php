<!DOCTYPE html>
<html>
<head>
    <title>Mes candidatures</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/mescandidatures.css">
</head>
<body>
    <h1 class="titre">Mes candidatures</h1>
    <div class="container">

        <!-- Candidatures envoyées -->
        <?php if (!empty($candidatures['sent'])): ?>
            <div class="premier_carre">
                <h3 class="sous-titre">Candidatures envoyées</h3>
                <?php foreach ($candidatures['sent'] as $candidature): ?>
                    <div class="sous-carre">
                        <div class="profil1">
                            <span class="initiales"><?= htmlspecialchars($candidature['prenom_utilisateur'][0]) . htmlspecialchars($candidature['nom_utilisateur'][0]) ?></span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($candidature['titre_annonce']); ?><br><?= htmlspecialchars($candidature['dateDebut_annonce']); ?></h4>
                            <h6><?= htmlspecialchars($candidature['prenom_utilisateur']); ?> <?= htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune candidature envoyée trouvée.</p>
        <?php endif; ?>
            
        <!-- Candidatures reçues -->
        <?php if (!empty($candidatures['received'])): ?>
            <div class="premier_carre">
                <h3 class="sous-titre">Candidatures reçues</h3>
                <?php foreach ($candidatures['received'] as $candidature): ?>
                    <div class="sous-carre">
                        <div class="profil1">
                            <span class="initiales"><?= htmlspecialchars($candidature['prenom_utilisateur'][0]) . htmlspecialchars($candidature['nom_utilisateur'][0]) ?></span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($candidature['titre_annonce']); ?><br><?= htmlspecialchars($candidature['dateDebut_annonce']); ?></h4>
                            <h6><?= htmlspecialchars($candidature['prenom_utilisateur']); ?> <?= htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune candidature reçue trouvée.</p>
        <?php endif; ?>
    </div>   
</body>
</html>
