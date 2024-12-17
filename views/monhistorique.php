<?php 
// Exemple d'ID utilisateur connecté
$userId = $_SESSION['user_id'] ?? 1;

require_once __DIR__ . '/../controllers/HistoriqueController.php';
$controller = new HistoriqueController();
$historique = $controller->index($userId);

// Liste de couleurs dynamiques
$couleurs = ['#500274', '#ece809', '#044c09', '#0705ae', '#e60ea9'];
$index = 0; // Index pour les couleurs

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon historique</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/mescandidatures.css">
</head>
<body>
    <h1 class="titre">Mon historique</h1>
    <div class="container">
        <!-- Historique des candidatures reçues -->
        <div class="premier_carre">
            <h3 class="sous-titre">Historique des candidatures reçues</h3>
            <?php if (!empty($historique['received'])): ?>
                <?php foreach ($historique['received'] as $candidature): ?>
                    <div class="sous-carre">
                        <div class="profil" style="background-color: <?= $couleurs[$index % count($couleurs)]; ?>;">
                            <span class="initiales">
                                <?= strtoupper($candidature['prenom_utilisateur'][0] . $candidature['nom_utilisateur'][0]); ?>
                            </span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($candidature['titre_annonce']); ?> <br> <?= htmlspecialchars($candidature['dateDebut_annonce']); ?></h4>
                            <h6>From <?= htmlspecialchars($candidature['prenom_utilisateur']) . ' ' . htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                        </div>
                    </div>
                    <?php $index++; // Incrémente l'index des couleurs ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune candidature reçue trouvée.</p>
            <?php endif; ?>
        </div>

        <!-- Historique des candidatures envoyées -->
        <div class="deuxieme_carre">
            <h3 class="sous-titre">Historique des candidatures envoyées</h3>
            <?php if (!empty($historique['sent'])): ?>
                <?php foreach ($historique['sent'] as $candidature): ?>
                    <div class="sous-carre">
                        <div class="profil" style="background-color: <?= $couleurs[$index % count($couleurs)]; ?>;">
                            <span class="initiales">
                                <?= strtoupper($candidature['prenom_utilisateur'][0] . $candidature['nom_utilisateur'][0]); ?>
                            </span>
                        </div>
                        <div class="details">
                            <h4><?= htmlspecialchars($candidature['titre_annonce']); ?> <br> <?= htmlspecialchars($candidature['dateDebut_annonce']); ?></h4>
                            <h6>To <?= htmlspecialchars($candidature['prenom_utilisateur']) . ' ' . htmlspecialchars($candidature['nom_utilisateur']); ?></h6>
                        </div>
                    </div>
                    <?php $index++; // Incrémente l'index des couleurs ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune candidature envoyée trouvée.</p>
            <?php endif; ?>
        </div>
    </div>   
</body>
</html>
