<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié</title>
    <!-- CSS Spécifique -->
    <link rel="stylesheet" href="/Petbesties/public/css/style_mdp.css">
</head>
<body>
    <main>
        <h1>Mot de passe oublié</h1>
        <div class="form-container">
            <form id="reset-password-form">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="jane@example.com" required>

                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" placeholder="********" required>

                <label for="confirm-password">Confirmation du nouveau mot de passe</label>
                <input type="password" id="confirm-password" placeholder="********" required>
                <span class="error-message" id="error-message">Les mots de passe ne correspondent pas.</span>

                <button type="submit" class="btn">NEXT</button>
            </form>
            <p class="disclaimer">
                En me connectant ou en m'inscrivant, j'accepte les 
                <a href="#">Conditions Générales de Service</a> et la 
                <a href="#">Politique de Confidentialité</a> de PetBesties.
                Je consens à recevoir des e-mails et des communications marketing
                de la part de PetBesties et de ses affiliés et je confirme avoir 18 ans ou plus.
            </p>
        </div>
    </main>
</body>
</html>
