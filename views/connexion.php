<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/Petbesties/public/css/style_mdp.css">
</head>
<body>
    <main>
        <h1>Connexion</h1>
        <div class="form-container">
            <form method="POST" action="/PetBesties/connexion">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="jane@example.com" required>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="********" required>

                <button type="submit" class="btn">Next</button>
            </form>
            <p class="disclaimer">
                En me connectant ou en m'inscrivant, j'accepte les Conditions Générales de Service et la Politique 
                de Confidentialité de PetBesties. Je consens à recevoir des e-mails et des communications 
                marketing de la part de PetBesties et de ses affiliés et je confirme avoir 18 ans ou plus.
            </p>
        </div>
    </main>
</body>
</html>
