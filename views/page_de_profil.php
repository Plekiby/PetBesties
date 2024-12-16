<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetBesties Profile</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/page_de_profil.css">
    </head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-image">
        <img id = "profile-pic" src="/petbesties/public/images/image_profil.png" alt="Profile Image">
        <button id = "upload-btn" class="add-image">+ Add an Image</button>
        <!-- Input caché pour sélectionner un fichier image -->
        <input type="file" id="file-input" style="display:none;" accept="image/*">
    </div>


        <div class="profile-name">
            <input type="text" id="first-name" placeholder="Prénom" />
            <input type="text" id="last-name" placeholder="Nom" />
            <button id="validate-name">Valider</button>
            <p id="name-error" style="color: red; display: none;"></p>
        </div>


        <div class="profile-type">
            <button id = "pet-sitter" class="active">Pet Sitter</button>
            <button id = "pet-owner">Pet Owner</button>
        </div>
    </div> 

    <div class="profile-description">
    <p id="bio-description">"Please select your identity as either a Pet Owner or a Pet Sitter, so we can update your bio accordingly. Your choice will help us tailor your profile to better suit your needs."!</p>
    <button id = "submit-bio " class="submit">Soumettre</button>
    <p id="confirmation-message" style="color: green; display: none;">Le statut de votre bio a été bien modifié !</p>
    </div>


    
    
    <div class="mission-form">
        <h3>Select Your Mission</h3>
    <!-- Role Selector -->
        <label for="role">Role:</label>
        <select id="role">
            <option value="sitter">Pet Sitter</option>
            <option value="owner">Pet Owner</option>
        </select>

    <!-- Animal Type Selector -->
        <label for="animal">Type of Animal:</label>
        <select id="animal">
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="bird">Bird</option>
            <option value="other">Other</option>
        </select>

    <!-- Date -->
        <label for="date">Date:</label>
        <input type="date" id="date">

    <!-- Address -->
        <label for="adresse">Address:</label>
        <input type="text" id="adresse" placeholder="Enter address here">

    <!-- Submit Button -->
        <button id="validate-mission">Submit Mission</button>

    <!-- Result Display Area -->
        <p id="mission-result" style="color: green; display: none;"></p>
    </div>




    <div class="annonces">
        <h3>Dernières annonces</h3>
        <div class="annonce">
            <div class="annonce-header">
                <img src="/petbesties/public/images/Value.png" alt="Stars">
                <p>Pet Sitter Pro</p>
            </div>
            <p>USER 123</p>
            <p>il y a 2 jours</p>
        </div>
        <div class="annonce">
            <div class="annonce-header">
                <img src="/petbesties/public/images/Value.png" alt="Stars">
                <p>Câlins Garantis</p>
            </div>
            <p>USER 456</p>
            <p>il y a 2 jours</p>
        </div>
        <div class="annonce">
            <div class="annonce-header">
                <img src="/petbesties/public/images/Value.png" alt="Stars">
                <p>Compagnie Fidèle</p>
            </div>
            <p>USER 789</p>
            <p>il y a 2 jours</p>
        </div>
    </div>
</div>

<!-- premier script JS qui correspond à "profile-name, creation de profil">  -->
<script>
 // ceci est le code responsible de la partie description de mon code en liason avec les deux boutons 

    // Récupérer les éléments nécessaires
    const petSitterBtn = document.getElementById('pet-sitter');
    const petOwnerBtn = document.getElementById('pet-owner');
    const bioDescription = document.getElementById('bio-description');
    const submitBtn = document.getElementById('submit-bio');
    const confirmationMessage = document.getElementById('confirmation-message');

    // Fonction pour mettre à jour la description du profil
    petSitterBtn.addEventListener('click', () => {
        bioDescription.innerHTML = "Hello,I am a passionate pet sitter, ready to take care of your pets with attention and affection. Your companion will be in good hands!";
    });

    petOwnerBtn.addEventListener('click', () => {
        bioDescription.innerHTML = "Hello, I am a pet owner; I have a pet and I'm looking for someone who can take care of it with love and care.";
    });

    // Fonction pour soumettre le bio et cacher le bouton une fois cliqué
    submitBtn.addEventListener('click', () => {
        submitBtn.style.display = 'none'; // Cache le bouton "Soumettre"
        confirmationMessage.style.display = 'block'; // Affiche le message de confirmation

        // Optionnel : tu peux aussi désactiver l'édition du bio après soumission si nécessaire
        bioDescription.setAttribute('contenteditable', 'false'); // Empêche toute modification du bio après soumission
    });

// ceci est la fin de cette fct 


    // Sélection des éléments
    const firstName = document.getElementById("first-name");
    const lastName = document.getElementById("last-name");
    const validateBtn = document.getElementById("validate-name");
    const errorDisplay = document.getElementById("name-error");

    // Fonction de validation
    validateBtn.addEventListener("click", () => {
        const firstNameValue = firstName.value.trim();
        const lastNameValue = lastName.value.trim();

        if (firstNameValue === "" || lastNameValue === "") {
            errorDisplay.textContent = "Veuillez remplir tous les champs !!! ";
            errorDisplay.style.display = "block";
        } else {
            errorDisplay.style.display = "none";
            alert(`Bienvenue ${firstNameValue} ${lastNameValue} !`);
        }
    });


    // ==========================
// 1. Gestion de l'upload d'image avec prévisualisation
// ==========================
    document.getElementById("upload-btn").addEventListener("click", function() {
        document.getElementById("file-input").click(); // Ouvre le sélecteur de fichiers
    });

    document.getElementById("file-input").addEventListener("change", function(event) {
        const file = event.target.files[0]; // Récupère le fichier sélectionné
        if (file) {
            const reader = new FileReader(); // Crée un lecteur de fichier
            reader.onload = function(e) {
                document.getElementById("profile-pic").src = e.target.result; // Met à jour l'image avec l'aperçu
            }
            reader.readAsDataURL(file); // Lit le fichier et génère une URL
        }
    });


// code pour la partie formulaire 

    document.getElementById('validate-mission').addEventListener('click', function () {
    // Récupération des valeurs des champs
        const role = document.getElementById('role').value;
        const animal = document.getElementById('animal').value;
        const date = document.getElementById('date').value;
        const adresse = document.getElementById('adresse').value;

    // Vérification des champs vides
        if (!role || !animal || !date || !adresse) {
            alert('Veuillez remplir tous les champs correctement.');
            return;
        }

    // Affichage du résultat
        const result = `Mission confirmée pour un rôle de "${role}" avec un "${animal}" le ${date} à l'adresse "${adresse}".`;
        const resultElement = document.getElementById('mission-result');
        resultElement.textContent = result; // Ajoute le texte
        resultElement.style.display = 'block'; // Rend le résultat visible
    });

// code pour la partie recuperation des annonces . 
    /* function loadAnnonces() {
        const xhr = new XMLHttpRequest(); // Crée une requête HTTP
        xhr.open("GET", "annonces.php", true); // Appelle le fichier PHP qui retourne les annonces
        xhr.onload = function() {
            if (xhr.status === 200) {
                const annonces = JSON.parse(xhr.responseText); // Convertit la réponse JSON en objet JS
                const container = document.getElementById("annonces-container");
                container.innerHTML = ""; // Vide le conteneur pour ajouter les nouvelles annonces

            // Boucle sur chaque annonce et crée du HTML pour l'afficher
                annonces.forEach(annonce => {
                    const annonceHTML = `
                        <div class="annonce">
                            <div class="annonce-header">
                                <img src="/petbesties/public/images/Value.png" alt="Stars">
                                <p>${annonce.titre}</p>
                            </div>
                            <p>${annonce.user}</p>
                            <p>${annonce.date}</p>
                        </div>
                `   ;
                    container.innerHTML += annonceHTML; // Ajoute chaque annonce dans le conteneur
                });
            }
        };
        xhr.send(); // Envoie la requête au serveur
    }

    // Appelle la fonction loadAnnonces dès que la page est chargée
    window.onload = loadAnnonces;*/


   



</script>

</body>
</html>
