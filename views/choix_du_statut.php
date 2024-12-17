<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix du Statut</title>
    <link rel="stylesheet" href="/Petbesties/public/css/style_choix.css">
</head>
<body>
    <main class="status-selection">
        <h1>Choix du Statut</h1>
        <form class="status-form">
            <input type="email" placeholder="jane@example.com" class="input-field" required>
            <input type="password" placeholder="********" class="input-field" required>

            <div class="status-toggle">
                <label class="toggle-option">
                    <input type="radio" name="status" value="PetOwner" checked>
                    <span>PetOwner</span>
                </label>
                <label class="toggle-option">
                    <input type="radio" name="status" value="PetSitter">
                    <span>PetSitter</span>
                </label>
            </div>

            <button type="submit" class="btn-next">NEXT</button>
        </form>
        <p class="terms">
            En me connectant ou en m'inscrivant, j'accepte les 
            <a href="#">Conditions Générales de Service</a> et la 
            <a href="#">Politique de Confidentialité</a> de PetBesties. 
            Je consens à recevoir des e-mails et des communications marketing de la part de PetBesties et de ses affiliés et je confirme avoir 18 ans ou plus.
        </p>
    </main>
</body>
</html>






