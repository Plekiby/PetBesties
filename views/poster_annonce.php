<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster une Annonce</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/page_de_profil.css">
    <style>
    /* ...existing styles... */

    .annonce-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 500px;
        margin: 0 auto;
    }

    .annonce-form input, .annonce-form textarea, .annonce-form select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .annonce-form button {
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .annonce-form button:hover {
        background-color: #45a049;
    }

    /* ...existing styles... */
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- ...existing header and navigation... -->

        <div class="annonce-form-container">
            <h2>Poster une Nouvelle Annonce</h2>
            <form class="annonce-form" method="POST" action="/PetBesties/poster_annonce">
                <label for="titre">Titre de l'annonce:</label>
                <input type="text" id="titre" name="titre" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="dateDebut">Date de début:</label>
                <input type="date" id="dateDebut" name="dateDebut" required>

                <label for="duree">Durée (en jours):</label>
                <input type="number" id="duree" name="duree" required>

                <label for="tarif">Tarif (€):</label>
                <input type="number" step="0.01" id="tarif" name="tarif" required>

                <label for="gardiennage">Gardiennage:</label>
                <select id="gardiennage" name="gardiennage" required>
                    <option value="">Sélectionnez une option</option>
                    <?php foreach ($gardiennages as $gardiennage): ?>
                        <option value="<?php echo htmlspecialchars($gardiennage['Id_Gardiennage']); ?>">
                            <?php echo htmlspecialchars($gardiennage['Nom_Gardiennage']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="promenade">Promenade:</label>
                <select id="promenade" name="promenade" required>
                    <option value="">Sélectionnez une option</option>
                    <?php foreach ($promenades as $promenade): ?>
                        <option value="<?php echo htmlspecialchars($promenade['Id_Promenade']); ?>">
                            <?php echo htmlspecialchars($promenade['Nom_Promenade']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="adresse">Adresse:</label>
                <select id="adresse" name="adresse" required>
                    <option value="">Sélectionnez une adresse existante</option>
                    <?php foreach ($adresses as $adresse): ?>
                        <option value="<?php echo htmlspecialchars($adresse['Id_Adresse']); ?>">
                            <?php echo htmlspecialchars($adresse['numero_adresse'] . ' ' . $adresse['rue_adresse'] . ', ' . $adresse['nom_adresse']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p>Ou ajoutez une nouvelle adresse:</p>
                <label for="numero">Numéro:</label>
                <input type="number" id="numero" name="numero" min="1">

                <label for="rue">Rue:</label>
                <input type="text" id="rue" name="rue">

                <label for="nom">Ville:</label>
                <input type="text" id="nom" name="nom">

                <label for="complement">Complément:</label>
                <input type="text" id="complement" name="complement">

                <label for="latitude">Latitude:</label>
                <input type="text" id="latitude" name="latitude">

                <label for="longitude">Longitude:</label>
                <input type="text" id="longitude" name="longitude">

                <button type="submit">Poster l'annonce</button>
            </form>
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
        </div>
    </div>

    <!-- ...existing scripts... -->
</body>
</html>