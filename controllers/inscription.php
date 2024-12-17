<?php
// Inclure la classe Database
require_once 'database.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $prenom_utilisateur = $_POST['prenom_utilisateur'];
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $email_utilisateur = $_POST['email_utilisateur'];
    $mdp_utilisateur = $_POST['mdp_utilisateur'];
    $telephone_utilisateur = isset($_POST['telephone_utilisateur']) ? $_POST['telephone_utilisateur'] : null;
    $type_utilisateur = isset($_POST['type_utilisateur']) ? $_POST['type_utilisateur'] : null;
    $rib_utilisateur = isset($_POST['rib_utilisateur']) ? $_POST['rib_utilisateur'] : null;
    $age = $_POST['age'];
    $code_postal = $_POST['code_postal'];

    // Hash du mot de passe pour plus de sécurité
    $mdp_utilisateur_hache = password_hash($mdp_utilisateur, PASSWORD_DEFAULT);

    // Obtenir la connexion à la base de données via la classe Database
    $db = Database::getInstance();
    $conn = $db->getConnection();

    try {
        // Préparer la requête d'insertion (ajustée avec les bons noms de colonnes)
        $sql = "INSERT INTO utilisateurs (prenom_utilisateur, nom_utilisateur, email_utilisateur, mdp_utilisateur, 
                telephone_utilisateur, type_utilisateur, rib_utilisateur, age, code_postal)
                VALUES (:prenom_utilisateur, :nom_utilisateur, :email_utilisateur, :mdp_utilisateur, 
                :telephone_utilisateur, :type_utilisateur, :rib_utilisateur, :age, :code_postal)";
        
        $stmt = $conn->prepare($sql);

        // Lier les paramètres à la requête SQL
        $stmt->bindParam(':prenom_utilisateur', $prenom_utilisateur);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email_utilisateur', $email_utilisateur);
        $stmt->bindParam(':mdp_utilisateur', $mdp_utilisateur_hache);
        $stmt->bindParam(':telephone_utilisateur', $telephone_utilisateur);
        $stmt->bindParam(':type_utilisateur', $type_utilisateur);
        $stmt->bindParam(':rib_utilisateur', $rib_utilisateur);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':code_postal', $code_postal);

        // Exécuter la requête
        $stmt->execute();

        // Afficher un message de succès
        echo "Inscription réussie !";
    } catch (PDOException $e) {
        // Afficher une erreur en cas de problème avec l'insertion
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    }
}
?>
