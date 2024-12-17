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
            <div class="sousmenu">
                
                <input type="checkbox" id="itineraire" class="sousmenu-toggle">
                <label for="itineraire" class="sousmenu-label">Choix de l'itinéraire</label>
                <div class="sousmenu-content">
                    <input type="text" placeholder="Départ" />
                    <input type="text" placeholder="Etape 1" />
                    <input type="text" placeholder="Etape 2" />
                    <button type="button">Ajouter une étape</button>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5255.5927442908005!2d2.1177804766536705!3d48.80486487132495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67d94d7b14c75%3A0x538fcc15f59ce8f!2sCh%C3%A2teau%20de%20Versailles!5e0!3m2!1sfr!2sfr!4v1730648791509!5m2!1sfr!2sfr" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <button type="button">OK</button>
                </div>

                
                <input type="checkbox" id="debut-promenade" class="sousmenu-toggle">
                <label for="debut-promenade" class="sousmenu-label">Validation du début de la promenade</label>
                <div class="sousmenu-content">
                	<button type="button">Start</button>
                    
                </div>

                
                <div class="sousmenu">
    <input type="checkbox" id="photo-toggle" class="sousmenu-toggle">
    <label for="photo-toggle" class="sousmenu-label">Télécharger une photo</label>
    <div class="sousmenu-content">
        <p>Choisissez une photo à télécharger :</p>
       
        <input type="file" id="imageUpload" accept="image/*">
        
       
        <button onclick="displayImage()">Télécharger et Afficher</button>

       
        <div id="imagePreview" style="margin-top: 15px;">
            <p>Aucune image sélectionnée pour l'instant.</p>
        </div>
    </div>
</div>

               
                <input type="checkbox" id="fin-promenade" class="sousmenu-toggle">
                <label for="fin-promenade" class="sousmenu-label">Validation de la fin de la promenade</label>
                <div class="sousmenu-content">
                	<button type="button">Stop</button>
                    
         
                </div>

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
    </div>
    
</body>
</html>
