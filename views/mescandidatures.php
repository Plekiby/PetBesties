<!DOCTYPE html>
<html>
<head>
    <title>Mes candidatures</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/mescandidatures.css">
    <style>
        /* Styles supplémentaires pour le bouton (optionnel) */
        .prestation-btn {
            background-color: #128f8b; /* Couleur de fond */
            color: #ffffff; /* Couleur du texte */
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .prestation-btn:hover {
            background-color: #513b56; /* Couleur au survol */
        }

        /* Styles pour le profil */
        .profil {
            width: 40px;
            height: 40px;
            background-color: #128f8b; /* Couleur de fond du cercle */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #ffffff;
            margin-right: 10px;
            text-transform: uppercase;
            overflow: hidden;
            box-sizing: border-box;
        }

        /* Styles pour les initiales */
        .initiales {
            display: block;
        }

        /* Styles supplémentaires pour l'apparence générale */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f0f4f8;
        }

        .titre {
            text-align: center;
            font-family: Arial, sans-serif;
            margin-top: 40px;
            color: #128f8b;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 20px; 
            margin-top: 40px;
            width: 80%;
            max-width: 1000px;
        }

        .sous-titre {
            text-align: center;
            font-family: Arial, sans-serif;
            margin-top: 20px;
            color: #ffffff;
        }

        .premier_carre {
            background-color: #128f8b; 
            padding: 20px;
            border-radius: 5px;
        }

        .sous-carre {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .details h4 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
            color: #333;
        }

        .details h6 {
            margin: 0;
            font-size: 12px;
            color: #555;
        }
    </style>
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
                        <div class="profil">
                            <span class="initiales"><?= htmlspecialchars($candidature['prenom_utilisateur'][0]) . htmlspecialchars($candidature['nom_utilisateur'][0]) ?></span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($candidature['titre_annonce']); ?><br><?= htmlspecialchars($candidature['dateDebut_annonce']); ?></h4>
                            <h6><?= htmlspecialchars($candidature['prenom_utilisateur']); ?> <?= htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                            <!-- Bouton pour aller à la page prestations -->
                            <button class="prestation-btn" onclick="window.location.href='/PetBesties/prestations'">Voir la prestation</button>
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
                        <div class="profil">
                            <span class="initiales"><?= htmlspecialchars($candidature['prenom_utilisateur'][0]) . htmlspecialchars($candidature['nom_utilisateur'][0]) ?></span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($candidature['titre_annonce']); ?><br><?= htmlspecialchars($candidature['dateDebut_annonce']); ?></h4>
                            <h6><?= htmlspecialchars($candidature['prenom_utilisateur']); ?> <?= htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                            <!-- Bouton pour aller à la page prestations -->
                            <button class="prestation-btn" onclick="window.location.href='/PetBesties/prestations'">Voir les prestations</button>
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
