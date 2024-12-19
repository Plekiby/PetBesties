<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster une Annonce</title>
    <style>
        .container-forms {
            display: flex;
            gap: 50px;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            flex-wrap: wrap;
        }

        .form-block {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 350px;
        }

        .form-block h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .form-block label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        .form-block input, .form-block select, .form-block textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-block button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .form-block button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            font-weight: bold;
        }

        .message.error {
            color: red;
        }

        .message.success {
            color: green;
        }
    </style>
</head>
<body>
<div class="container-forms">

    <!-- Formulaire pour créer un nouvel animal -->
    <div class="form-block">
        <h2>Créer un Nouvel Animal</h2>
        <form method="POST" action="/PetBesties/poster_annonce">
            <input type="hidden" name="action" value="create_animal">

            <label for="nom_animal">Nom de l'animal:</label>
            <input type="text" id="nom_animal" name="nom_animal" required>

            <label for="race_animal">Race de l'animal:</label>
            <input type="text" id="race_animal" name="race_animal" required>

            <button type="submit">Créer l'animal</button>

            <?php if (isset($error) && strpos($error, 'animal') !== false): ?>
                <p class="message error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>

    <!-- Formulaire pour créer une nouvelle adresse -->
    <div class="form-block">
        <h2>Créer une Nouvelle Adresse</h2>
        <form method="POST" action="/PetBesties/poster_annonce">
            <input type="hidden" name="action" value="create_address">

            <label for="numero">Numéro:</label>
            <input type="number" id="numero" name="numero_adresse" required>

            <label for="rue">Rue:</label>
            <input type="text" id="rue" name="rue_adresse" required>

            <label for="nom">Ville:</label>
            <input type="text" id="nom" name="nom_adresse" required>

            <label for="complement">Complément:</label>
            <input type="text" id="complement" name="complement_adresse">

            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" readonly>

            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" readonly>

            <button type="button" id="geocodeBtn">Géocoder l'adresse</button>
            <button type="submit">Créer l'adresse</button>

            <?php if (isset($error) && strpos($error, 'adresse') !== false): ?>
                <p class="message error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>

    <!-- Formulaire pour poster l'annonce -->
<!-- Formulaire pour poster l'annonce -->
<div class="form-block">
    <h2>Poster une Nouvelle Annonce</h2>
    <form method="POST" action="/PetBesties/poster_annonce">
        <input type="hidden" name="action" value="post_annonce">

        <label for="type_annonce">Type d'annonce:</label>
        <select id="type_annonce" name="type_annonce" required>
            <option value="">Sélectionnez le type</option>
            <option value="gardiennage">Garde</option>
            <option value="promenade">Promenade</option>
        </select>

        <label for="animal">Sélectionnez votre animal:</label>
        <select id="animal" name="Id_Animal" required>
            <option value="">Sélectionnez un animal</option>
            <?php foreach ($animals as $animal): ?>
                <option value="<?php echo htmlspecialchars($animal['Id_Animal']); ?>">
                    <?php echo htmlspecialchars($animal['nom_animal'] . ' (' . $animal['race_animal'] . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="adresse">Sélectionnez votre adresse:</label>
        <select id="adresse" name="Id_Adresse" required>
            <option value="">Sélectionnez une adresse</option>
            <?php foreach ($adresses as $adresse): ?>
                <option value="<?php echo htmlspecialchars($adresse['Id_Adresse']); ?>">
                    <?php echo htmlspecialchars($adresse['numero_adresse'] . ' ' . $adresse['rue_adresse'] . ', ' . $adresse['nom_adresse']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="date_debut_annonce">Date de début:</label>
        <input type="date" id="date_debut_annonce" name="date_debut_annonce" required>

        <label for="duree_annonce">Durée (heures):</label>
        <input type="number" id="duree_annonce" name="duree_annonce" min="1" required>

        <label for="price_annonce">Prix (€):</label>
        <input type="number" id="price_annonce" name="price_annonce" min="0" step="0.01" required>

        <label for="details_annonce">Détails:</label>
        <textarea name="details_annonce" id="details_annonce" required></textarea>

        <button type="submit">Poster l'annonce</button>

        <?php if (isset($success)): ?>
            <p class="message success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <?php if (isset($error) && !$success && strpos($error, 'annonce') !== false): ?>
            <p class="message error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</div>


<script>
document.getElementById('geocodeBtn').addEventListener('click', function() {
    const numero = document.getElementById('numero').value;
    const rue = document.getElementById('rue').value;
    const ville = document.getElementById('nom').value;

    if (!numero || !rue || !ville) {
        alert("Veuillez renseigner le numéro, la rue et la ville avant de géocoder.");
        return;
    }

    const query = encodeURIComponent(`${numero} ${rue} ${ville}`);

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                document.getElementById('latitude').value = result.lat;
                document.getElementById('longitude').value = result.lon;
            } else {
                alert("Aucune coordonnée trouvée pour cette adresse.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erreur lors du géocodage. Vérifiez votre connexion ou réessayez plus tard.");
        });
});
</script>

</body>
</html>
