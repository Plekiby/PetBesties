<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Liste des messages à gauche -->
        <div class="messages-list">
            <h2>Conversations</h2>
            <ul>
                <li onclick="showConversation('paul')">Paul Pogba</li>
                <li onclick="showConversation('antoine')">Antoine Griezman</li>
                <li onclick="showConversation('hugo')">Hugo Lloris</li>
                <li onclick="showConversation('samuel')">Samuel Umtiti</li>
                <li onclick="showConversation('didier')">Didier Deschamps</li>
                <li onclick="showConversation('ngolo')">Ngolo Kanté</li>
            </ul>
        </div>

        <!-- Contenu des conversations à droite -->
        <div class="content">
            <div id="paul" class="conversation">
                <h3>Paul Pogba</h3>
                <p>Je suis très intéressé</p>
                <p class="sent">Merci à vous !</p>
            </div>
            <div id="antoine" class="conversation">
                <h3>Antoine Griezman</h3>
                <p>Comment ça va ?</p>
            </div>
            <div id="hugo" class="conversation">
                <h3>Hugo Lloris</h3>
                <p>Rendez-vous demain pour l'entraînement.</p>
            </div>
            <div id="samuel" class="conversation">
                <h3>Samuel Umtiti</h3>
                <p>On reste concentrés.</p>
            </div>
            <div id="didier" class="conversation">
                <h3>Didier Deschamps</h3>
                <p>On prépare bien la compétition.</p>
            </div>
            <div id="ngolo" class="conversation">
                <h3>Ngolo Kanté</h3>
                <p>Je donne toujours le maximum.</p>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour afficher la conversation sélectionnée
        function showConversation(id) {
            // Cacher toutes les conversations
            const conversations = document.querySelectorAll('.conversation');
            conversations.forEach(conv => conv.style.display = 'none');

            // Afficher la conversation sélectionnée
            const activeConv = document.getElementById(id);
            activeConv.style.display = 'block';
        }

        // Par défaut afficher la première conversation
        window.onload = () => showConversation('paul');
    </script>
</body>
</html>
