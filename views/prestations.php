<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/petbesties/public/css/stylepresta.css">
    <title>Prestations</title>
</head>
<body>
    <div class="rectangle">
        <div class="entête">
            <h1>Prestations</h1>
        </div>
        
        <div class="menu-container">
            <!-- Onglet Choix de l'itinéraire -->
            <div class="sousmenu">
                <input type="checkbox" id="itineraire" class="sousmenu-toggle">
                <label for="itineraire" class="sousmenu-label">Choix de l'itinéraire</label>
                <div class="sousmenu-content" id="contenu">
                    <input type="text" placeholder="Départ" />
                    <input type="text" placeholder="Étape 1" />
                    <input type="text" placeholder="Étape 2" />
                    <button onclick="ajouterEtape()">Ajouter une étape</button>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5255.5927442908005!2d2.1177804766536705!3d48.80486487132495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67d94d7b14c75%3A0x538fcc15f59ce8f!2sCh%C3%A2teau%20de%20Versailles!5e0!3m2!1sfr!2sfr!4v1730648791509!5m2!1sfr!2sfr" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <button type="button">OK</button>
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
    // Fonction pour ajouter une nouvelle étape
    function ajouterEtape() {
    // Crée un nouvel input
    var nouvelInput = document.createElement("input");
    nouvelInput.type = "text";
    nouvelInput.placeholder = "Ajout d'une étape";

    // Récupère le bouton "Ajouter une étape"
    var boutonAjouter = document.querySelector("button[onclick='ajouterEtape()']");

    // Insère le nouvel input juste avant le bouton
    boutonAjouter.parentNode.insertBefore(nouvelInput, boutonAjouter);
}


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
