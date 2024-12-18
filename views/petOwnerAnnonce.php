<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Owner Annonce</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
            background-color: #009688;
            color: #ffffff;
            padding: 20px;
        }

        .filter-bar div {
            margin: 5px;
        }

        .content {
            display: flex;
        }

        .map-container {
            flex: 3; /* Augmenté pour prendre plus de largeur */
            height: 600px;
            margin-right: 20px; /* Augmenté pour plus d'espace à droite */
        }

        .results {
            flex: 2; /* Augmenté pour prendre plus de largeur */
            background-color: #ffffff;
            padding: 20px; /* Augmenté pour plus de confort */
            border-radius: 8px;
            height: 600px;
            /* Hauteur fixe définie pour permettre le scroll */
            overflow-y: auto;
            /* Ajout du scroll vertical */
        }


        #map {
            height: 100%;
            width: 100%;
            border-radius: 8px;
        }

        .result-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            cursor: pointer;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .result-item .info {
            flex-grow: 1;
        }

        .result-item .info h4 {
            margin: 0;
            font-size: 1.1em;
        }

        .result-item .info p {
            margin: 2px 0;
            color: #666;
        }

        .result-item:hover {
            background-color: #f0f0f0;
        }

        .load-more {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #5c6bc0;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .map-container {
            flex: 2;
            height: 600px;
            margin-right: 10px;
        }

        .results {
            flex: 1;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 8px;
            height: 600px;
            overflow-y: auto;
        }

        #map {
            height: 100%;
            width: 100%;
            border-radius: 8px;
        }

        .result-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            cursor: pointer;
        }

        .result-item:hover {
            background-color: #f0f0f0;
        }

        .result-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .result-item .info {
            flex-grow: 1;
        }

        .result-item .info h4 {
            margin: 0;
            font-size: 1.1em;
        }

        .result-item .info p {
            margin: 2px 0;
            color: #666;
        }

        .load-more {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #5c6bc0;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="filter-bar">
        <form method="GET" action="">
            <div>
                <label><input type="checkbox" name="gardiennage" value="1"> Gardiennage</label>
                <label><input type="checkbox" name="hebergement" value="1"> Hébergement</label>
            </div>
            <div>
                <label>Prix max :</label>
                <input type="range" name="prix_max" min="0" max="200" value="">
            </div>
            <div>
                <label>Type d'animal :</label>
                <label><input type="checkbox" name="chien" value="1"> Chien</label>
                <label><input type="checkbox" name="chat" value="1"> Chat</label>
                <label><input type="checkbox" name="oiseau" value="1"> Oiseau</label>
                <label><input type="checkbox" name="rongeur" value="1"> Rongeur</label>
            </div>
            <button type="submit">Filtrer</button>
        </form>
    </div>

    <div class="content">
        <div class="map-container">
            <div id="map" style="height: 600px;"></div>
        </div>
        <div class="results">
            <?php if (!empty($annonces)): ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="result-item" onclick="map.setView([<?= htmlspecialchars($annonce['latitude']); ?>, <?= htmlspecialchars($annonce['longitude']); ?>], 15)">
                        <img src="/petbesties/public/images/sitter.png" alt="Sitter">
                        <div class="info">
                            <h4><?= htmlspecialchars($annonce['titre_annonce']); ?></h4>
                            <p><?= htmlspecialchars($annonce['prenom_utilisateur']) . ' ' . htmlspecialchars($annonce['nom_utilisateur']); ?></p>
                            <p>À partir de <?= htmlspecialchars($annonce['tarif_annonce']); ?> €</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune annonce trouvée.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialisation de la carte avec Leaflet et OpenStreetMap
        const map = L.map('map').setView([48.8566, 2.3522], 12); // Coordonnées de Paris

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ajout des marqueurs pour chaque annonce
        const annonces = <?= json_encode($annonces ?? []); ?>;

        if (annonces.length > 0) {
            annonces.forEach((annonce) => {
                if (annonce.latitude && annonce.longitude) {
                    L.marker([annonce.latitude, annonce.longitude]).addTo(map)
                        .bindPopup(`<b>${annonce.titre_annonce}</b><br>${annonce.prenom_utilisateur} ${annonce.nom_utilisateur}<br>${annonce.tarif_annonce} €`);
                }
            });
        }
    </script>
    <?php include __DIR__ . '/footer.php'; ?>

</body>

</html>