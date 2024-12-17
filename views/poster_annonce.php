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
        grid-template-columns: 1fr; /* Full width */
        gap: 20px;
        width: 100%; /* Ensure full width */
        max-width: 800px; /* Adjust as needed */
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .annonce-form label {
        font-weight: bold;
    }

    .annonce-form select, 
    .annonce-form input, 
    .annonce-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .annonce-form button {
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
    }

    /* ...existing styles... */
    </style>
    <script>
    function toggleAnnonceFields() {
        var type = document.getElementById('type_annonce').value;
        var gardiennageFields = document.getElementById('gardiennage_fields');
        var promenadeFields = document.getElementById('promenade_fields');
        var newAddressFields = document.getElementById('new_address_fields');

        if (type === 'gardiennage') {
            gardiennageFields.style.display = 'block';
            promenadeFields.style.display = 'none';
            generateTitle('Garde');
        } else if (type === 'promenade') {
            promenadeFields.style.display = 'block';
            gardiennageFields.style.display = 'none';
            generateTitle('Promenade');
        } else {
            gardiennageFields.style.display = 'none';
            promenadeFields.style.display = 'none';
            document.getElementById('titre_annonce').value = '';
        }
    }

    function generateTitle(type) {
        var animalSelect = document.getElementById('animal');
        var animalName = animalSelect.options[animalSelect.selectedIndex].text.split(' (')[0];
        document.getElementById('titre_annonce').value = type + ' de ' + animalName;
    }

    function toggleAddressCreation() {
        var adresseSelect = document.getElementById('adresse');
        var newAddressFields = document.getElementById('new_address_fields');

        if (adresseSelect.value === '') {
            newAddressFields.style.display = 'block';
        } else {
            newAddressFields.style.display = 'none';
        }
    }

    window.onload = function() {
        document.getElementById('type_annonce').addEventListener('change', toggleAnnonceFields);
        document.getElementById('animal').addEventListener('change', function() {
            var type = document.getElementById('type_annonce').value;
            if (type) {
                generateTitle(type.charAt(0).toUpperCase() + type.slice(1));
            }
        });
        document.getElementById('adresse').addEventListener('change', toggleAddressCreation);
    };
    </script>
</head>
<body>
    <div class="profile-container">
        <!-- ...existing header and navigation... -->

        <div class="annonce-form-container">
            <form class="annonce-form" method="POST" action="/PetBesties/poster_annonce">
                <h2>Poster une Nouvelle Annonce</h2>

                <!-- Titre de l'annonce (auto-généré) -->
                <label for="titre_annonce">Titre de l'annonce:</label>
                <input type="text" id="titre_annonce" name="titre_annonce" readonly required>

                <!-- Type d'annonce -->
                <label for="type_annonce">Type d'annonce:</label>
                <select id="type_annonce" name="type_annonce" required>
                    <option value="">Sélectionnez le type</option>
                    <option value="gardiennage">Garde</option>
                    <option value="promenade">Promenade</option>
                </select>

                <!-- Selection de l'animal -->
                <label for="animal">Sélectionnez votre animal:</label>
                <select id="animal" name="Id_Animal" required>
                    <option value="">Sélectionnez un animal</option>
                    <?php foreach ($animals as $animal): ?>
                        <option value="<?php echo htmlspecialchars($animal['Id_Animal']); ?>">
                            <?php echo htmlspecialchars($animal['nom_animal'] . ' (' . $animal['race_animal'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Selection de l'adresse -->
                <label for="adresse">Sélectionnez votre adresse:</label>
                <select id="adresse" name="Id_Adresse" required>
                    <option value="">Sélectionnez une adresse</option>
                    <?php foreach ($adresses as $adresse): ?>
                        <option value="<?php echo htmlspecialchars($adresse['Id_Adresse']); ?>">
                            <?php echo htmlspecialchars($adresse['numero_adresse'] . ' ' . $adresse['rue_adresse'] . ', ' . $adresse['nom_adresse']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Nouveau champ pour ajouter une adresse seulement si aucune adresse sélectionnée -->
                <div id="new_address_fields" style="display:none;">
                    <p>Ajoutez une nouvelle adresse:</p>

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
                </div>

                <!-- Détails de l'annonce -->
                <label for="details_annonce">Détails:</label>
                <textarea name="details_annonce" id="details_annonce" required></textarea>

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