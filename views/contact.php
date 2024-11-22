<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <link rel="stylesheet" href="style_contact.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="/Users/Oscar/Desktop/ISEP/A1/APP/Site WEB/logo.png" alt="PetBesties">
            </div>
            <ul class="menu">
                <li><a href="#">PetSitter</a></li>
                <li><a href="#">PetOwner</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="actions">
                <button class="btn">S'inscrire</button>
                <button class="btn">Connexion</button>
            </div>
        </nav>
    </header>

    <main>
        <section class="contact-section">
            <h1>Contactez-nous</h1>
            <form class="contact-form">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" placeholder="...">

                <label for="nom">Nom</label>
                <input type="text" id="nom" placeholder="...">

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="...">

                <label for="message">Message</label>
                <textarea id="message" placeholder="..."></textarea>

                <button type="submit" class="submit-btn">Envoyer</button>
            </form>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="social-icons">
                <a href="#"><img src="icon-instagram.png" alt="Instagram"></a>
                <a href="#"><img src="icon-youtube.png" alt="YouTube"></a>
                <a href="#"><img src="icon-linkedin.png" alt="LinkedIn"></a>
            </div>
            <div class="footer-links">
                <div>
                    <h4>Use cases</h4>
                    <ul>
                        <li>UI design</li>
                        <li>UX design</li>
                        <li>Wireframing</li>
                        <li>Diagramming</li>
                        <li>Brainstorming</li>
                        <li>Online whiteboard</li>
                        <li>Team collaboration</li>
                    </ul>
                </div>
                <div>
                    <h4>Explore</h4>
                    <ul>
                        <li>Design</li>
                        <li>Prototyping</li>
                        <li>Development features</li>
                        <li>Design systems</li>
                        <li>Collaboration features</li>
                        <li>Design process</li>
                        <li>FigJam</li>
                    </ul>
                </div>
                <div>
                    <h4>Resources</h4>
                    <ul>
                        <li>Blog</li>
                        <li>Best practices</li>
                        <li>Colors</li>
                        <li>Color wheel</li>
                        <li>Support</li>
                        <li>Developers</li>
                        <li>Resource library</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

