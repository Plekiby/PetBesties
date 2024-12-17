<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require 'db_connection.php';
$conversationId = $_POST['conversation_id'];
$userId = $_POST['user_id'];
$content = $_POST['content'];

$stmt = $pdo->prepare("INSERT INTO message (Id_Conversation, Id_Utilisateur, contenu_message) 
                       VALUES (?, ?, ?)");
$stmt->execute([$conversationId, $userId, $content]);
echo "Message envoyé !";
?>

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
        <script>
        // Detecte l'appui sur une touche du clavier
        document.addEventListener("keypress", keyPressed); 
        // Envoi le message si appui sur enter
        function keyPressed(event) { 
            if (event.keyCode === 13) {
                postMessage();
            }
        }
        // Envoi le message et le nom vers le fichier API
        function postMessage() {
            var name =  encodeURIComponent(document.getElementById('name').value);
            var message =  encodeURIComponent(document.getElementById('message').value);
            var requestURL = 'API.php?name=' + name + '&message=' + message;
            var request = new XMLHttpRequest();
            request.open('GET', requestURL);
            request.send();
            document.getElementById('message').value = '';
        }
        // Affiche le contenu retourné du fichier API
        function displayChat() {
            var requestURL = 'API.php?display';
            var request = new XMLHttpRequest();
            request.open('GET', requestURL);
            request.send();
            request.onload = function() {
                document.getElementById("chat").innerHTML = request.responseText;
            }
        }
        // Actualise le chat à intervalle de 200 millisecondes
        setInterval(displayChat, 200);
    </script>
    
    </script>
    <script>
    function loadMessages(conversationId) {
    $.ajax({
        url: 'fetch_messages.php',
        method: 'GET',
        data: { conversation_id: conversationId },
        success: function(data) {
            $('#messageArea').html(data); // Afficher les messages
        }
    });
    }
    </script>
    <?php
require 'db_connection.php'; // Connexion à la base
$conversationId = $_GET['conversation_id'];
$stmt = $pdo->prepare("SELECT m.contenu_message, m.date_message, u.prenom_utilisateur 
                       FROM message m 
                       JOIN utilisateur u ON m.Id_Utilisateur = u.Id_utilisateur
                       WHERE m.Id_Conversation = ?");
$stmt->execute([$conversationId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    echo "<p><strong>{$message['prenom_utilisateur']}:</strong> {$message['contenu_message']} <em>{$message['date_message']}</em></p>";
}
?>

    
</body>
</html>
