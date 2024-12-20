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
                <img id="profile-pic" src="<?= htmlspecialchars($utilisateur['image_profil'] ?? '/petbesties/public/images/image_profil.png'); ?>" alt="Profile Image">
                <button id="upload-btn" class="add-image">+</button>
                <input type="file" id="file-input" style="display:none;" accept="image/*">
            </div>

            <div class="profile-details">
                <form id="update-profile-form">
                    <label for="profile-first-name">Prénom</label>
                    <input type="text" id="profile-first-name" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom_utilisateur'] ?? ''); ?>"><br>

                    <label for="profile-last-name">Nom</label> 
                    <input type="text" id="profile-last-name" name="nom" value="<?= htmlspecialchars($utilisateur['nom_utilisateur'] ?? ''); ?>"><br>

                    <label for="profile-email">Email</label>
                    <input type="email" id="profile-email" name="email" value="<?= htmlspecialchars($utilisateur['email_utilisateur'] ?? ''); ?>"><br>

                    <label for="profile-phone">Téléphone</label>
                    <input type="text" id="profile-phone" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone_utilisateur'] ?? ''); ?>"><br>

                    <button type="submit" id="save-profile">Enregistrer</button>
                    <p id="update-success" style="color: green; display: none;">Informations mises à jour avec succès !</p>
                    <p id="update-error" style="color: red; display: none;"></p>
                </form>
            </div>

            <div class="profile-type">
                <button id="pet-sitter" class="<?= isset($utilisateur['type_utilisateur']) && $utilisateur['type_utilisateur'] == 1 ? 'active' : ''; ?>">Pet Sitter</button>
                <button id="pet-owner" class="<?= isset($utilisateur['type_utilisateur']) && $utilisateur['type_utilisateur'] == 0 ? 'active' : ''; ?>">Pet Owner</button>
            </div>
        </div> 

        <div class="profile-description">
            <p id="bio-description">
                <?php 
                if(isset($utilisateur['type_utilisateur'])){
                    if($utilisateur['type_utilisateur'] == 1){
                        echo "Bonjour, je suis un Pet Sitter passionné, prêt à prendre soin de vos animaux avec attention et affection. Votre compagnon sera entre de bonnes mains !";
                    } else {
                        echo "Bonjour, je suis un Pet Owner; j'ai un animal et je cherche quelqu'un qui peut en prendre soin avec amour et attention.";
                    }
                } else {
                    echo "Veuillez sélectionner votre identité en tant que Pet Owner ou Pet Sitter pour que nous puissions mettre à jour votre bio en conséquence. Votre choix nous aidera à adapter votre profil pour mieux répondre à vos besoins.";
                }
                ?>
            </p>
            <button id="submit-bio" class="submit">Soumettre</button>
            <p id="confirmation-message" style="color: green; display: none;">Le statut de votre bio a été bien modifié !</p>
        </div>

        <div class="animaux">
            <h3>Vos Animaux</h3>
            <div class="animaux-list">
                <?php if (!empty($animaux)): ?>
                    <?php foreach ($animaux as $animal): ?>
                        <div class="animal">
                            <img src="<?= htmlspecialchars($animal['image'] ?? '/petbesties/public/images/default_animal.png'); ?>" alt="<?= htmlspecialchars($animal['nom_animal']); ?>">
                            <p><?= htmlspecialchars($animal['nom_animal']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun animal ajouté.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="annonces">
            <h3>Vos Annonces</h3>
            <div class="annonces-list">
                <?php if (!empty($annonces)): ?>
                    <?php foreach ($annonces as $annonce): ?>
                        <div class="annonce">
                            <div class="annonce-header">
                                <img src="/petbesties/public/images/default_animal.png" alt="Stars">
                                <p><?= htmlspecialchars($annonce['titre_annonce']); ?></p>
                            </div>
                            <p><?= htmlspecialchars($annonce['prenom_utilisateur'] . ' ' . $annonce['nom_utilisateur']); ?></p>
                            <p><?= htmlspecialchars(date('d/m/Y', strtotime($annonce['dateDebut_annonce']))); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune annonce publiée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        // Gestion de la description du profil
        const petSitterBtn = document.getElementById('pet-sitter');
        const petOwnerBtn = document.getElementById('pet-owner');
        const bioDescription = document.getElementById('bio-description');
        const submitBtn = document.getElementById('submit-bio');
        const confirmationMessage = document.getElementById('confirmation-message');

        petSitterBtn.addEventListener('click', () => {
            bioDescription.innerHTML = "Bonjour, je suis un Pet Sitter passionné, prêt à prendre soin de vos animaux avec attention et affection. Votre compagnon sera entre de bonnes mains !";
            petSitterBtn.classList.add('active');
            petOwnerBtn.classList.remove('active');
        });

        petOwnerBtn.addEventListener('click', () => {
            bioDescription.innerHTML = "Bonjour, je suis un Pet Owner; j'ai un animal et je cherche quelqu'un qui peut en prendre soin avec amour et attention.";
            petOwnerBtn.classList.add('active');
            petSitterBtn.classList.remove('active');
        });

        submitBtn.addEventListener('click', () => {
            submitBtn.style.display = 'none';
            confirmationMessage.style.display = 'block';
            bioDescription.setAttribute('contenteditable', 'false');
        });

        // Gestion de l'upload d'image avec prévisualisation
        document.addEventListener("DOMContentLoaded", function() {
            const profilePic = document.getElementById("profile-pic");
            const uploadBtn = document.getElementById("upload-btn");
            const fileInput = document.getElementById("file-input");

            // Charger l'image depuis localStorage au démarrage
            const savedImage = localStorage.getItem("profileImage");
            if (savedImage) {
                profilePic.src = savedImage;
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
                        const imageURL = e.target.result;
                        profilePic.src = imageURL;
                        localStorage.setItem("profileImage", imageURL);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

        // Récupération des données utilisateur depuis la session
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/PetBesties/api/user-data')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error("Erreur:", data.error);
                    } else {
                        document.getElementById("profile-first-name").value = data.prenom || "Prénom";
                        document.getElementById("profile-last-name").value = data.nom || "Nom";
                        document.getElementById("profile-email").value = data.email || "email@example.com";
                        document.getElementById("profile-phone").value = data.telephone || "Numéro de téléphone";
                    }
                })
                .catch(error => console.error("Erreur lors de la récupération des données:", error));
        });

        // Mise à jour du profil via AJAX
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
