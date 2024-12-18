<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetBesties Profile</title>
    <link rel="stylesheet" type="text/css" href="/petbesties/public/css/page_de_profil.css">
    <style>
    
</style>
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



        <!-- code html qui recupere les identifiants du user-->
        <div class="profile-details">
            <form id="update-profile-form">
                <div class="form-group">
                    <label for="profile-first-name">First Name</label>
                    <input type="text" id="profile-first-name" name="prenom" value="First Name">
                </div>

                <div class="form-group">
                    <label for="profile-last-name">Last Name</label>
                    <input type="text" id="profile-last-name" name="nom" value="Last Name">
                </div>

                <div class="form-group">
                    <label for="profile-email">Email Address</label>
                    <input type="email" id="profile-email" name="email" value="email@example.com">
                </div>

                <div class="form-group">
                    <label for="profile-phone">Phone Number</label>
                    <input type="text" id="profile-phone" name="telephone" value="Phone Number">
                </div>

                <button type="submit" id="save-profile">Save</button>
                <p id="update-success" style="color: green; display: none;">Informations mises à jour avec succès !</p>
                <p id="update-error" style="color: red; display: none;"></p>
            </form>
        </div>

        <!-- page_de_profile.php -->



        <div class="profile-name">
            <input type="text" id="first-name" placeholder="Prénom" />
            <input type="text" id="last-name" placeholder="Nom" />
            <button id="validate-name">Modify</button>
            <p id="name-error" style="color: red; display: none;"></p>
        </div>

        <div class="profile-type">
            <button id = "pet-sitter" class="active">Pet Sitter</button>
            <button id = "pet-owner">Pet Owner</button>
        </div>
    </div> 

    <div class="profile-description">
    <p id="bio-description">"Please select your identity as either a Pet Owner or a Pet Sitter, so we can update your bio accordingly. Your choice will help us tailor your profile to better suit your needs.!"</p>
    <button id = "submit-bio" class="submit">Submit</button>
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
document.addEventListener("DOMContentLoaded", function() {
    const profilePic = document.getElementById("profile-pic");
    const uploadBtn = document.getElementById("upload-btn");
    const fileInput = document.getElementById("file-input");

    // Charger l'image depuis localStorage au démarrage
    const savedImage = localStorage.getItem("profileImage");
    if (savedImage) {
        profilePic.src = savedImage; // Si une image est sauvegardée, l'afficher
    }

    // Événement pour ouvrir le sélecteur de fichiers
    uploadBtn.addEventListener("click", function() {
        fileInput.click();
    });

    // Événement pour lire et afficher l'image sélectionnée
    fileInput.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageURL = e.target.result; // Base64 de l'image
                profilePic.src = imageURL;

                // Sauvegarder l'image dans localStorage
                localStorage.setItem("profileImage", imageURL);
            }
            reader.readAsDataURL(file);
        }
    });
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




    // Fonction pour récupérer les données utilisateur depuis la session

    document.addEventListener("DOMContentLoaded", function() {
    // Requête AJAX pour récupérer les données utilisateur
        fetch('/PetBesties/api/user-data')
            .then(response => response.json()) // Convertir la réponse en JSON
            .then(data => {
                if (data.error) {
                    console.error("Erreur:", data.error);
                } else {
                // Insérer les valeurs dans les champs HTML avec les bons noms de champs
                    document.getElementById("profile-first-name").value = data.prenom || "Prénom";
                    document.getElementById("profile-last-name").value = data.nom || "Nom";
                    document.getElementById("profile-email").value = data.email || "email@example.com";
                    document.getElementById("profile-phone").value = data.telephone || "Numéro de téléphone";
                }
            })
            .catch(error => console.error("Erreur lors de la récupération des données:", error));
    });

    document.getElementById('update-profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/PetBesties/api/update-user', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                document.getElementById('update-success').style.display = 'block';
                document.getElementById('update-error').style.display = 'none';
            } else {
                document.getElementById('update-error').textContent = data.error;
                document.getElementById('update-error').style.display = 'block';
                document.getElementById('update-success').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('update-error').textContent = 'Une erreur est survenue.';
            document.getElementById('update-error').style.display = 'block';
            document.getElementById('update-success').style.display = 'none';
        });
    });

</script>

</body>
</html>
