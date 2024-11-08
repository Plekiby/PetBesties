<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetBesties</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="css/style.css">
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
            max-width: 1200px;
            margin: 20px auto;
            padding: 10px;
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
    <?php include __DIR__ . '/header.php'; ?>

    <div class="filter-bar">
    <form method="GET" action="">
        <div>
            <label><input type="checkbox" name="promenade" value="1" <?= isset($_GET['promenade']) ? 'checked' : '' ?>> Promenade</label>
            <label><input type="checkbox" name="hebergement" value="1" <?= isset($_GET['hebergement']) ? 'checked' : '' ?>> Hébergement</label>
        </div>
        <div>
            <label>Prix max :</label>
            <input type="range" name="prix_max" min="0" max="100" value="<?= isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : '50' ?>">
        </div>
        <div>
            <label>Type d'animal :</label>
            <label><input type="checkbox" name="chien" value="1" <?= isset($_GET['chien']) ? 'checked' : '' ?>> Chien</label>
            <label><input type="checkbox" name="chat" value="1" <?= isset($_GET['chat']) ? 'checked' : '' ?>> Chat</label>
            <label><input type="checkbox" name="oiseau" value="1" <?= isset($_GET['oiseau']) ? 'checked' : '' ?>> Oiseau</label>
            <label><input type="checkbox" name="rongeur" value="1" <?= isset($_GET['rongeur']) ? 'checked' : '' ?>> Rongeur</label>
        </div>
        <button type="submit">Filtrer</button>
    </form>
</div>

<div class="content">
    <div class="map-container">
        <div id="map"></div>
    </div>
    <div class="results">
        <?php foreach ($prestataires as $prestataire): ?>
            <div class="result-item">
                <img src="<?= htmlspecialchars($prestataire['photo']); ?>" alt="Photo de <?= htmlspecialchars($prestataire['nom']); ?>">
                <div class="info">
                    <h4><?= htmlspecialchars($prestataire['nom']); ?></h4>
                    <p><?= htmlspecialchars($prestataire['ville']); ?></p>
                    <p>À partir de <?= htmlspecialchars($prestataire['tarif']); ?> € par promenade</p>
                    <p>Note : <?= htmlspecialchars($prestataire['avis']); ?> (<?= htmlspecialchars($prestataire['nb_avis']); ?> avis)</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>



    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialisation de la carte avec Leaflet et OpenStreetMap
        const map = L.map('map').setView([48.8566, 2.3522], 12); // Coordonnées de Paris

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ajout des marqueurs pour chaque prestataire
        const locations = <?= json_encode($prestataires); ?>;

        locations.forEach((location) => {
            L.marker([location.latitude, location.longitude]).addTo(map)
                .bindPopup(`<b>${location.nom}</b><br>${location.tarif} € par promenade`);
        });
    </script>
    <?php include __DIR__ . '/footer.php'; ?>

</body>

</html>