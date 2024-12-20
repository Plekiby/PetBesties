<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($utilisateur['prenom_utilisateur'] . ' ' . $utilisateur['nom_utilisateur']); ?> - PetBesties</title>
    <link rel="stylesheet" type="text/css" href="/Petbesties/public/css/public_profile.css">
</head>
<body>

    <div class="profile-container">
        <!-- Informations de l'Utilisateur -->
        <div class="profile-header">
            <div class="profile-image">
                <img src="<?= htmlspecialchars($utilisateur['image_profil'] ?? '/Petbesties/public/images/default_profile.png'); ?>" alt="Image de Profil de <?= htmlspecialchars($utilisateur['prenom_utilisateur']); ?>">
            </div>

            <div class="profile-details">
                <h2><?= htmlspecialchars($utilisateur['prenom_utilisateur'] . ' ' . $utilisateur['nom_utilisateur']); ?></h2>
                <p>Email : <?= htmlspecialchars($utilisateur['email_utilisateur']); ?></p>
                <p>Téléphone : <?= htmlspecialchars($utilisateur['telephone_utilisateur']); ?></p>
                <p>Type : <?= $utilisateur['type_utilisateur'] == 1 ? 'Pet Sitter' : 'Pet Owner'; ?></p>
            </div>
        </div>

        <!-- Section Animaux -->
        <div class="animaux">
            <h3>Animaux de <?= htmlspecialchars($utilisateur['prenom_utilisateur']); ?></h3>
            <div class="animaux-list">
                <?php if (!empty($animaux)): ?>
                    <?php foreach ($animaux as $animal): ?>
                        <div class="animal">
                            <img src="<?= htmlspecialchars($animal['image'] ?? '/Petbesties/public/images/default_animal.png'); ?>" alt="Image de <?= htmlspecialchars($animal['nom_animal']); ?>">
                            <p><?= htmlspecialchars($animal['nom_animal']); ?></p>
                            <p><?= htmlspecialchars($animal['race_animal']); ?></p>
                            <p>Âge : <?= htmlspecialchars($animal['age_animal']); ?> ans</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun animal à afficher.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Annonces -->
        <div class="annonces">
            <h3>Annonces de <?= htmlspecialchars($utilisateur['prenom_utilisateur']); ?></h3>
            <div class="annonces-list">
                <?php if (!empty($annonces)): ?>
                    <?php foreach ($annonces as $annonce): ?>
                        <div class="annonce">
                            <div class="annonce-header">
                                <h4><?= htmlspecialchars($annonce['titre_annonce']); ?></h4>
                                <p>Date de Publication : <?= htmlspecialchars(date('d/m/Y', strtotime($annonce['datePublication_annonce']))); ?></p>
                            </div>
                            <div class="annonce-body">
                                <p><?= htmlspecialchars($annonce['description_annonce']); ?></p>
                                <p>Tarif : <?= htmlspecialchars($annonce['tarif_annonce']); ?> €</p>
                                <p>Durée : <?= htmlspecialchars($annonce['duree_annonce']); ?> jours</p>
                                <p>Type : <?= $annonce['type_annonce'] == 1 ? 'Gardiennage' : 'Promenade'; ?></p>
                                <!-- Bouton pour postuler à l'annonce -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form action="/PetBesties/postuler" method="POST">
                                    <input type="hidden" name="annonce_id" value="<?= htmlspecialchars($annonce['Id_Annonce']); ?>">
                                    <button type="submit" class="postuler-btn">Postuler</button>
                                </form>
                            <?php else: ?>
                                <p>Veuillez vous connecter pour postuler.</p>
                            <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune annonce à afficher.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript (si nécessaire) -->
    <!-- Vous pouvez ajouter des scripts ici si vous avez besoin d'interactivité supplémentaire -->
    <script>// Fonction pour gérer la postulation (actuellement sans action)
        function postulerAnnonce() {
            alert('Candidature Soumise !');
            // Vous pouvez rediriger vers une page de postulation ou ouvrir un modal ici.
        }
    </script>
</body>
</html>
