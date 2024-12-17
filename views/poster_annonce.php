<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster une Annonce</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/page_de_profil.css">
    <style>
    /* ...existing styles... */

    .annonce-form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .annonce-form {
        display: grid;
        grid-template-columns: 2fr 3fr; /* Ajustement des proportions pour plus de largeur */
        gap: 20px;
        width: 90%; /* Augmentation de la largeur */
        max-width: 1200px;
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .annonce-form label {
        grid-column: span 2;
        font-weight: bold;
    }

    .annonce-form input, 
    .annonce-form textarea, 
    .annonce-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .annonce-form .full-width {
        grid-column: span 2;
    }

    .annonce-form button {
        grid-column: span 2;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .annonce-form button:hover {
        background-color: #45a049;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .annonce-form {
            grid-template-columns: 1fr;
        }

        .annonce-form .full-width {
            grid-column: span 1;
        }
    }

    /* ...existing styles... */
    </style>
    <script>
    function toggleAnnonceFields() {
        var type = document.getElementById('type_annonce').value;
        if (type === 'gardiennage') {
            document.getElementById('gardiennage_fields').style.display = 'block';
            document.getElementById('promenade_fields').style.display = 'none';
        } else if (type === 'promenade') {
            document.getElementById('promenade_fields').style.display = 'block';
            document.getElementById('gardiennage_fields').style.display = 'none';
        } else {
            document.getElementById('gardiennage_fields').style.display = 'none';
            document.getElementById('promenade_fields').style.display = 'none';
        }
    }
    </script>
</head>
<body>
    <div class="profile-container">
        <!-- ...existing header and navigation... -->

        <div class="annonce-form-container">
            <form class="annonce-form" method="POST" action="/PetBesties/poster_annonce">
                <h2 class="full-width">Poster une Nouvelle Annonce</h2>

                <label for="titre">Titre de l'annonce:</label>
                <input type="text" id="titre" name="titre_annonce" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description_annonce" required></textarea>

                <label for="dateDebut">Date de début:</label>
                <input type="date" id="dateDebut" name="dateDebut_annonce" required>

                <label for="duree">Durée (en jours):</label>
                <input type="number" id="duree" name="duree_annonce" required>

                <label for="tarif">Tarif (€):</label>
                <input type="number" step="0.01" id="tarif" name="tarif_annonce" required>

                <label for="type_annonce">Type d'Annonce:</label>
                <select name="type_annonce" id="type_annonce" onchange="toggleAnnonceFields()" required>
                    <option value="">Sélectionnez un type</option>
                    <option value="promenade">Promenade</option>
                    <option value="gardiennage">Gardiennage</option>
                </select>

                <div id="promenade_fields" style="display:none;">
                    <label for="promenade">Promenade:</label>
                    <select id="promenade" name="Id_Promenade">
                        <option value="">Sélectionnez une option</option>
                        <?php foreach ($promenades as $promenade): ?>
                            <option value="<?php echo htmlspecialchars($promenade['Id_Promenade']); ?>">
                                <?php echo htmlspecialchars($promenade['Nom_Promenade'] ?? 'Option sans nom'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="gardiennage_fields" style="display:none;">
                    <label for="gardiennage">Gardiennage:</label>
                    <select id="gardiennage" name="Id_Gardiennage">
                        <option value="">Sélectionnez une option</option>
                        <?php foreach ($gardiennages as $gardiennage): ?>
                            <option value="<?php echo htmlspecialchars($gardiennage['Id_Gardiennage']); ?>">
                                <?php echo htmlspecialchars($gardiennage['Nom_Gardiennage'] ?? 'Option sans nom'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label for="details_annonce">Détails:</label>
                <textarea name="details_annonce" id="details_annonce" required></textarea>

                <label for="adresse">Adresse existante:</label>
                <select id="adresse" name="Id_Adresse">
                    <option value="">Sélectionnez une adresse existante</option>
                    <?php foreach ($adresses as $adresse): ?>
                        <option value="<?php echo htmlspecialchars($adresse['Id_Adresse']); ?>">
                            <?php echo htmlspecialchars($adresse['numero_adresse'] . ' ' . $adresse['rue_adresse'] . ', ' . $adresse['nom_adresse']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <p class="full-width">Ou ajoutez une nouvelle adresse:</p>

                <label for="numero">Numéro:</label>
                <input type="number" id="numero" name="numero_adresse" min="1">

                <label for="rue">Rue:</label>
                <input type="text" id="rue" name="rue_adresse">

                <label for="nom">Ville:</label>
                <input type="text" id="nom" name="nom_adresse">

                <label for="complement">Complément:</label>
                <input type="text" id="complement" name="complement_adresse">

                <label for="latitude">Latitude:</label>
                <input type="text" id="latitude" name="latitude">

                <label for="longitude">Longitude:</label>
                <input type="text" id="longitude" name="longitude">

                <button type="submit">Poster l'annonce</button>

                <?php if (isset($success)): ?>
                    <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                    <!-- Display detailed error for development -->
                    <?php if (isset($detailed_error)): ?>
                        <p style="color: red;"><?php echo htmlspecialchars($detailed_error); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- ...existing scripts... -->
</body>
</html>