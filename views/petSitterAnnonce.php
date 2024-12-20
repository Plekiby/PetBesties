<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Owner Annonce</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <style>
        /* Votre CSS existant */
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
            padding: 20px;
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

        /* Styles for Popup Buttons */
        .popup-button {
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            color: #ffffff;
            transition: opacity 0.3s ease;
        }

        .popup-button.profil {
            background-color: #128f8b;
            margin-right: 5px;
        }

        .popup-button.postuler {
            background-color: #ff9800;
        }

        .popup-button:hover {
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }

            .map-container,
            .results {
                margin-right: 0;
                margin-bottom: 20px;
                height: 400px;
            }
        }
    </style>
</head>

<body>
    <div class="filter-bar">
        <form method="GET" action="/PetBesties/petsitter">
            <div>
                <label><input type="checkbox" name="type_annonce[]" value="0" <?= isset($_GET['type_annonce']) && in_array('0', $_GET['type_annonce']) ? 'checked' : '' ?>> Promenade</label>
                <label><input type="checkbox" name="type_annonce[]" value="1" <?= isset($_GET['type_annonce']) && in_array('1', $_GET['type_annonce']) ? 'checked' : '' ?>> Gardiennage</label>
            </div>
            <div>
                <label for="prix_max">Prix max :</label>
                <input type="range" id="prix_max" name="prix_max" min="0" max="200" value="<?= htmlspecialchars($_GET['prix_max'] ?? 200) ?>">
                <span id="prix_max_val"><?= htmlspecialchars($_GET['prix_max'] ?? 200) ?> €</span>
            </div>
            <div>
                <label>Type d'animal :</label>
                <label><input type="checkbox" name="type_animal[]" value="Chien" <?= isset($_GET['type_animal']) && in_array('Chien', $_GET['type_animal']) ? 'checked' : '' ?>> Chien</label>
                <label><input type="checkbox" name="type_animal[]" value="Chat" <?= isset($_GET['type_animal']) && in_array('Chat', $_GET['type_animal']) ? 'checked' : '' ?>> Chat</label>
                <label><input type="checkbox" name="type_animal[]" value="Oiseau" <?= isset($_GET['type_animal']) && in_array('Oiseau', $_GET['type_animal']) ? 'checked' : '' ?>> Oiseau</label>
                <label><input type="checkbox" name="type_animal[]" value="Rongeur" <?= isset($_GET['type_animal']) && in_array('Rongeur', $_GET['type_animal']) ? 'checked' : '' ?>> Rongeur</label>
                <label><input type="checkbox" name="type_animal[]" value="Lapin" <?= isset($_GET['type_animal']) && in_array('Lapin', $_GET['type_animal']) ? 'checked' : '' ?>> Lapin</label>
                <label><input type="checkbox" name="type_animal[]" value="Furet" <?= isset($_GET['type_animal']) && in_array('Furet', $_GET['type_animal']) ? 'checked' : '' ?>> Furet</label>
                <label><input type="checkbox" name="type_animal[]" value="Hamster" <?= isset($_GET['type_animal']) && in_array('Hamster', $_GET['type_animal']) ? 'checked' : '' ?>> Hamster</label>
                <label><input type="checkbox" name="type_animal[]" value="Serpent" <?= isset($_GET['type_animal']) && in_array('Serpent', $_GET['type_animal']) ? 'checked' : '' ?>> Serpent</label>
                <label><input type="checkbox" name="type_animal[]" value="Tortue" <?= isset($_GET['type_animal']) && in_array('Tortue', $_GET['type_animal']) ? 'checked' : '' ?>> Tortue</label>
            </div>
            <button type="submit">Filtrer</button>
        </form>
    </div>

    <div class="content">
        <div class="map-container">
            <div id="map"></div>
        </div>
        <div class="results">
            <?php if (!empty($annonces)): ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="result-item" onclick="map.setView([<?= htmlspecialchars($annonce['latitude']); ?>, <?= htmlspecialchars($annonce['longitude']); ?>], 15)">
                        <img src="/petbesties/public/images/sitter.png" alt="Sitter">
                        <div class="info">
                            <h4><?= htmlspecialchars($annonce['titre_annonce']); ?></h4>
                            <p><?= htmlspecialchars($annonce['prenom_utilisateur']) . ' ' . htmlspecialchars($annonce['nom_utilisateur']); ?></p>
                            <p>Race de l'animal: <?= htmlspecialchars($annonce['race_animal']); ?></p>
                            <p>À partir de <?= htmlspecialchars($annonce['tarif_annonce']); ?> €</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune annonce trouvée.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <script>
        document.getElementById('prix_max').addEventListener('input', function() {
            document.getElementById('prix_max_val').textContent = this.value + ' €';
        });

        // Initialisation de la carte avec Leaflet et OpenStreetMap
        const map = L.map('map').setView([48.8566, 2.3522], 12); // Coordonnées de Paris

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ajout des marqueurs pour chaque annonce
        const annonces = <?= json_encode($annonces ?? []); ?>;

        if (annonces.length > 0) {
            const markers = L.markerClusterGroup();

            annonces.forEach((annonce) => {
                if (annonce.latitude && annonce.longitude) {
                    const popupContent = `
                        <div style="font-family: Arial, sans-serif;">
                            <b>${annonce.titre_annonce}</b><br>
                            ${annonce.prenom_utilisateur} ${annonce.nom_utilisateur}<br>
                            Race de l'animal: ${annonce.race_animal}<br>
                            À partir de ${annonce.tarif_annonce} €<br>
                            <div style="margin-top: 10px;">
                                <button class="popup-button profil" onclick="window.location.href='/PetBesties/profil/${annonce.Id_utilisateur}'">
                                    Voir le profil
                                </button>
                                <button class="popup-button postuler" onclick="postulerAnnonce(${annonce.Id_Annonce})">
                                    Postuler
                                </button>
                            </div>
                        </div>
                    `;
                    const marker = L.marker([annonce.latitude, annonce.longitude])
                        .bindPopup(popupContent);
                    markers.addLayer(marker);
                }
            });

            map.addLayer(markers);
        }

        // Fonction pour gérer la postulation (actuellement sans action)
        function postulerAnnonce(annonceId) {
            alert('Candidature Soumise !');
            // Vous pouvez rediriger vers une page de postulation ou ouvrir un modal ici.
        }
    </script>

</body>

</html>
