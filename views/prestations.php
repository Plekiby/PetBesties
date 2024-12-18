<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'appg1d_petbesties';
$username = 'root'; // À remplacer par ton utilisateur MySQL
$password = 'root'; // Mot de passe MySQL
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération de l'ID utilisateur (par exemple, ID fixe ou passé par GET)
$id_annonce = 1; // À remplacer dynamiquement selon l'ID de l'annonce
$query = $pdo->prepare("SELECT Id_utilisateur, titre_annonce FROM annonce WHERE Id_Annonce = :id");
$query->execute(['id' => $id_annonce]);
$annonce = $query->fetch();

if ($annonce) {
    // On récupère le nom de l'utilisateur
    $user_id = $annonce['Id_utilisateur'];
    $query_user = $pdo->prepare("SELECT nom_utilisateur FROM utilisateurs WHERE Id_utilisateur = :id_user");
    $query_user->execute(['id_user' => $user_id]);
    $user = $query_user->fetch();
    $nom_utilisateur = $user ? $user['nom_utilisateur'] : "Inconnu";
} else {
    $nom_utilisateur = "Inconnu";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/petbesties/public/css/stylepresta.css">
    <title>Prestations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <div class="rectangle">
        <div class="entête">
    <h1>Prestations</h1>
    <p style="font-size: 18px; color: #e0e0e0;">Promenade de : <strong><?php echo htmlspecialchars($nom_utilisateur); ?></strong></p>
</div>


        <div class="menu-container">
            <!-- Onglet Choix de l'itinéraire -->
            <div class="sousmenu">
                <input type="checkbox" id="itineraire" class="sousmenu-toggle">
                <label for="itineraire" class="sousmenu-label">Choix de l'itinéraire</label>
                <div class="sousmenu-content" id="contenu">
                    <div id="itineraire-container">
                        <input type="text" placeholder="Départ" id="depart" />
                        <input type="text" placeholder="Étape 1" class="etape" />
                        <input type="text" placeholder="Arrivée" id="arrivee" />
                        <button onclick="ajouterEtape()">Ajouter une étape</button>
                        <button onclick="validerItineraire()">OK</button>
                    </div>
                    <div id="map" style="width: 100%; height: 300px; margin-top: 10px;"></div>
                </div>  
            </div>

            <!-- Onglet Début de promenade -->
            <div class="sousmenu">
                <input type="checkbox" id="debut-promenade" class="sousmenu-toggle">
                <label for="debut-promenade" class="sousmenu-label">Validation du début de la promenade</label>
                <div class="sousmenu-content">
                    <p id="chrono">00:00:00</p>
                    <button id="startButton" type="button">Start</button>
                </div>
            </div>

            <!-- Onglet Fin de promenade -->
            <div class="sousmenu">
                <input type="checkbox" id="fin-promenade" class="sousmenu-toggle">
                <label for="fin-promenade" class="sousmenu-label">Validation de la fin de la promenade</label>
                <div class="sousmenu-content">
                    <button id="stopButton" type="button" disabled>Stop</button>
                    <p id="finPromenadeMessage" style="display: none;">Fin de promenade validée.</p>
                </div>
            </div>

            <!-- Onglet Télécharger une photo -->
            <div class="sousmenu">
                <input type="checkbox" id="photo-toggle" class="sousmenu-toggle">
                <label for="photo-toggle" class="sousmenu-label">Télécharger une photo</label>
                <div class="sousmenu-content">
                    <p>Choisissez une photo à télécharger : (Taille max : 50 Mo)</p>
                    <input type="file" id="imageUpload" accept="image/*">
                </div>
            </div>

            <!-- Onglet Avis -->
            <div class="sousmenu">
                <input type="checkbox" id="avis" class="sousmenu-toggle">
                <label for="avis" class="sousmenu-label">Avis</label>
                <div class="sousmenu-content">
                    <div class="review-card">
                        <div class="stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star empty">&#9733;</span>
                        </div>
                        <div class="review-text">Sérieuse !</div>
                        <div class="review-details">
                            <p>Sébastien</p>
                            <p>il y a 10h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    let map = L.map("map").setView([48.804864, 2.120355], 13);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors"
}).addTo(map);

let markers = [];

// Fonction pour ajouter un champ d'étape
function ajouterEtape() {
    const container = document.getElementById("itineraire-container");
    const nouvelInput = document.createElement("input");
    nouvelInput.type = "text";
    nouvelInput.placeholder = "Étape " + (document.querySelectorAll(".etape").length + 1);
    nouvelInput.classList.add("etape");
    container.insertBefore(nouvelInput, container.children[container.children.length - 2]);
}

// Fonction pour valider et afficher l'itinéraire
function validerItineraire() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    const depart = document.getElementById("depart").value.trim();
    const arrivee = document.getElementById("arrivee").value.trim();
    const etapes = Array.from(document.querySelectorAll(".etape")).map(e => e.value.trim());
    const points = [depart, ...etapes, arrivee].filter(Boolean);

    points.forEach((adresse, index) => {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(adresse)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const [lat, lon] = [data[0].lat, data[0].lon];
                    const marker = L.marker([lat, lon])
                        .addTo(map)
                        .bindPopup(`${index === 0 ? "Départ" : index === points.length - 1 ? "Arrivée" : `Étape ${index}`}: ${adresse}`);
                    markers.push(marker);
                    if (index === 0) map.setView([lat, lon], 13);
                }
            });
    });
}

// *** Code ajouté ici pour rafraîchir la carte lors de l'ouverture de l'onglet ***
document.getElementById('itineraire').addEventListener('change', function() {
    if (this.checked) {
        setTimeout(() => map.invalidateSize(), 300); // Rafraîchit la taille après affichage
    }
});
// Chronomètre
    let timer; 
    let seconds = 0;

    function formatTime(seconds) {
        let h = Math.floor(seconds / 3600);
        let m = Math.floor((seconds % 3600) / 60);
        let s = seconds % 60;
        return h.toString().padStart(2, '0') + ":" + m.toString().padStart(2, '0') + ":" + s.toString().padStart(2, '0');
    }

    function startChrono() {
        if (!timer) {
            timer = setInterval(() => {
                seconds++;
                document.getElementById("chrono").textContent = formatTime(seconds);
            }, 1000);
            document.getElementById("startButton").disabled = true;
            document.getElementById("stopButton").disabled = false;
        }
    }

    function stopChrono() {
        clearInterval(timer);
        timer = null;
        document.getElementById("stopButton").disabled = true;
        document.getElementById("finPromenadeMessage").style.display = "block";
    }

    document.getElementById("startButton").addEventListener("click", startChrono);
    document.getElementById("stopButton").addEventListener("click", stopChrono);
</script>
</body>
</html>
