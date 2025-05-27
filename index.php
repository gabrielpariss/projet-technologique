<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taverne du Jeux</title>
    <link rel="icon" type="image/x-icon" href="Image/Favicon-Logoo.png">
    <link rel="stylesheet" href="style-menu.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-…TON_HASH…" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: orange;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            flex-wrap: wrap;
        }

        header h1 {
            font-size: 2em;
            text-align: center;
            font-family: Comic Sans MS;
            padding-left: 10%;
        }

        /* ---- Sidebar ---- */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #111;
            padding-top: 60px;
            transition: 0.3s;
            z-index: 1;
        }

        .sidebar.show {
            left: 0;
        }

        .sidebar a {
            padding: 12px 24px;
            display: block;
            text-decoration: none;
            color: #ccc;
            font-size: 18px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background-color: #333;
            color: white;
        }

        /* ---- Shift de la page ---- */
        .page-content {
            transition: margin-left 0.3s;
        }

        .page-content.shift {
            margin-left: 250px;
        }

        /* ---- Menu burger ---- */
        .menu-burger {
            position: fixed;
            top: 3%;
            left: 15px;
            z-index: 2;
            cursor: pointer;
        }

        .menu-icon div {
            width: 35px;
            height: 4px;
            background-color: #ccc;
            margin: 6px 0;
            transition: 0.4s;
        }

        .change .bar1 {
            transform: rotate(-45deg) translate(-8px, 8px);
        }

        .change .bar2 {
            opacity: 0;
        }

        .change .bar3 {
            transform: rotate(45deg) translate(-8px, -8px);
        }

        .right-header {
            display: flex;
            align-items: center;
            gap: 10%;
        }

        .right-header a {
            color: white;
            display: flex;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #34495e;
        }

        .right-header input[type="text"] {
            padding: 5px;
            border-radius: 4px;
            border: none;
        }

        .avatar {
            vertical-align: middle;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .profil-client {
            padding-right: 1%;
        }

        .profil-menu {
            cursor: pointer;
            background-color: #34495e;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .dropdown {
            position: absolute;
            right: 0;
            top: 40px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .dropdown a {
            padding: 10px;
            text-decoration: none;
            color: #2c3e50;
        }

        .dropdown a:hover {
            background-color: #f0f0f0;
        }

        .hidden {
            display: none;
        }

        nav {
            background-color: #ecf0f1;
            padding: 10px;
            width: 200px;
            position: absolute;
            top: 60px;
            left: 0;
            height: calc(100% - 60px);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        nav a {
            text-decoration: none;
            color: #2c3e50;
            padding: 10px;
            border-radius: 4px;
        }

        nav a:hover {
            background-color: #bdc3c7;
        }

        main {
            margin-top: 60px;
            padding: 20px;
        }

        .container {
            margin-bottom: 40px;
            justify-items: center;
            border-width: 1px;
            padding-bottom: 20px;
        }

        .container h2 {
            color: #111;
            text-align: center;
            margin-bottom: 20px;
        }

        .image-grid-jeux {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding-bottom: 40px;
        }

        .image-game {
            position: relative;
            width: 150px;
            height: 150px;
            overflow: hidden;
            cursor: pointer;
            border-radius: 5%;
        }

        .image-game img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }


        .image-grid-eve {
            display: flex;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            padding: 20px;
        }

        .image-event {
            position: relative;
            width: 150px;
            height: 150px;
            overflow: hidden;
            cursor: pointer;
            border-radius: 5%;
            cursor: pointer;
            text-align: center;
        }

        .image-event img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .overlay {
            position: absolute;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            width: 100%;
            text-align: center;
            padding: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-game:hover .overlay {
            opacity: 1;
        }

        .preview-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background-color: rgba(67, 67, 67, 0.9);
            color: whitesmoke;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 1000px;
            max-height: 400px;
            display: flex;
            gap: 20px;
        }

        .modal-image-container {
            align-self: center;
            flex: 0 0 50%;
        }

        .modal-image {
            width: 100%;
            position: sticky;
            top: 20px;
        }

        .modal-text {
            flex: 1;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-weight: bold;
            font-size: 40px;
            color: #ffffff;
            cursor: pointer;
        }

        .button-74 {
            background-color: #fbeee0;
            border: 2px solid #422800;
            border-radius: 30px;
            box-shadow: #422800 4px 4px 0 0;
            color: #422800;
            cursor: pointer;
            display: inline-block;
            font-weight: 600;
            font-size: 18px;
            padding: 0 18px;
            line-height: 50px;
            text-align: center;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            margin-top: 20px;
        }

        .button-74:hover {
            background-color: #fff;
        }

        .button-74:active {
            box-shadow: #422800 2px 2px 0 0;
            transform: translate(2px, 2px);
        }

        @media (min-width: 768px) {
            .button-74 {
                min-width: 120px;
                padding: 0 25px;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            header h1 {
                text-align: left;
                font-size: 1.5em;
            }

            .right-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
                width: 100%;
            }
        }

        .footer {
            background-color: #111;
            color: #aaa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            font-size: 14px;
            flex-wrap: wrap;
            border-top: 1px solid #333;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo {
            height: 40px;
            filter: grayscale(100%) brightness(1.5);
        }

        .avatar-footer {
            vertical-align: middle;
            width: 50px;
            height: 50px;
            border-radius: 20%;
        }

        .footer-right {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
        }

        .social-icons a img {
            height: 28px;
            margin: 0 5px;
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        .social-icons a img:hover {
            opacity: 1;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            margin: 0 8px;
            font-size: 13px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

<!-- Sidebar -->
<nav id="sideMenu" class="sidebar">
    <a href="catalogue.php">Profil</a>
    <a href="catalogue.php">Catalogue des jeux</a>
    <a href="evenements.php">Événements</a>
    <a href="inscription_evenement.php">Inscription événement</a>
</nav>

<!-- Contenu principal -->
<div class="page-content" id="pageWrapper">

    <header>
        <div class="menu-burger" onclick="toggleMenu(this)">
            <div class="menu-icon">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
        </div>

        <h1>La Taverne du Jeux</h1>
        <div class="right-header">
            <a href="#">Catalogues des Jeux</a>
            <a href="#">Événements</a>
            <a href="#">Événements</a>
            <input type="text" placeholder="Rechercher...">
        </div>
        <div class="profil-client">
            <div class="profil-menu" onclick="toggleProfil()">
                <img src="Image/Favicon-Logoo.png" alt="Avatar" class="avatar">
            </div>
            <div id="profilDropdown" class="dropdown hidden">
                <form id="loginForm" style="padding:1em; width:250px;">
                    <h3 style="margin-bottom:.5em; color:#2c3e50;">Se connecter</h3>
                    <input type="text" name="identifiant" placeholder="Votre e-mail" required
                           style="width:100%; padding:.6em; margin-bottom:.5em; border:1px solid #ccc; border-radius:4px;">
                    <div style="position:relative;">
                        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required
                               style="width:100%; padding:.6em; border:1px solid #ccc; border-radius:4px;">
                        <span id="togglePwd"
                              style="position:absolute; right:.5em; top:50%; transform:translateY(-50%); cursor:pointer; color:#aaa;">
              <i class="fas fa-eye"></i>
            </span>
                    </div>
                    <button type="submit"
                            style="width:100%; margin-top:1em; padding:.7em; background:#e74c3c; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                        CONNEXION
                    </button>
                    <div id="loginMessage" style="margin-top:.5em; color:#c0392b; font-size:.9em;"></div>
                    <div style="margin-top:1em; font-size:.9em; display:flex; justify-content:space-between;">
                        <a href="Connexion/mot-de-passe-oublie.php" style="color:#2980b9; text-decoration:none;">
                            Mot de passe oublié ?
                        </a>
                        <a href="inscription_site.php" style="color:#2980b9; text-decoration:none;">
                            Créer un compte
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="game-container">
            <h2>Les 5 derniers jeux</h2>
            <div class="image-grid-jeux">
                <div class="image-game"><img src="Image/Logo-Boutique.png">
                    <div class="overlay">Monopoly</div>
                </div>
                <div class="image-game"><img src="Image/Logo-Boutique.png">
                    <div class="overlay">Uno</div>
                </div>
                <div class="image-game"><img src="Image/Logo-Boutique.png">
                    <div class="overlay">Echec</div>
                </div>
                <div class="image-game"><img src="Image/Logo-Boutique.png">
                    <div class="overlay">Puzzle</div>
                </div>
                <div class="image-game"><img src="Image/Logo-Boutique.png">
                    <div class="overlay">Pokemon</div>
                </div>
            </div>
        </div>

        <div class="event-container">
            <h2>Les 5 derniers événements</h2>
            <div class="image-grid-eve">
                <div class="image-event" data-id="1">
                    <img src="Image/Logo-Boutique.png" alt="Monopoly" class="preview-image">
                </div>
                <div class="image-event" data-id="2">
                    <img src="Image/Logo-Boutique.png" alt="Uno" class="preview-image">
                </div>
                <div class="image-event" data-id="3">
                    <img src="Image/Logo-Boutique.png" alt="Echec" class="preview-image">
                </div>
                <div class="image-event" data-id="4">
                    <img src="Image/Logo-Boutique.png" alt="Puzzle" class="preview-image">
                </div>
                <div class="image-event" data-id="5">
                    <img src="Image/Logo-Boutique.png" alt="Pokemon" class="preview-image">
                </div>
            </div>

            <div class="modal" id="imageModal">
                <div class="modal-content">
                    <span class="close" id="closeModal">&times;</span>
                    <div class="modal-image-container">
                        <img id="modalImage" src="" alt="Image Zoom" class="modal-image">
                    </div>
                    <div class="modal-text" id="modalText">
                        <!-- Description dynamique -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-left">
            <img src="Image/Logo-Boutique.png" alt="Taverne du Jeux" class="avatar-footer">
            <span>&copy; 2025 - Tous droits réservés.</span>
        </div>

        <div class="footer-right">
            <div class="social-icons">
                <a href="#"><img src="Image/icon-facebook.png" alt="Facebook"/></a>
                <a href="#"><img src="Image/icon-youtube.png" alt="YouTube"/></a>
                <a href="#"><img src="Image/icon-X.png" alt="Twitter"/></a>
                <a href="#"><img src="Image/icon-instagram.png" alt="Instagram"/></a>
            </div>
            <div class="footer-links">
                <a href="#">Endorsements</a>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
                <a href="#">Mentions légales</a>
            </div>
        </div>
    </footer>

    <!-- 🔽 Script déplacé ici -->
    <script>
        function toggleMenu(iconWrapper) {
            const icon = iconWrapper.querySelector('.menu-icon');
            icon.classList.toggle("change");

            document.getElementById("sideMenu").classList.toggle("show");
            document.getElementById("pageWrapper").classList.toggle("shift");
        }

        function toggleProfil() {
            document.getElementById("profilDropdown").classList.toggle("hidden");
        }

        window.onload = function () {
            const modal = document.getElementById("imageModal");
            const modalImg = document.getElementById("modalImage");
            const modalText = document.getElementById("modalText");
            const closeBtn = document.getElementById("closeModal");
            const imageCards = document.querySelectorAll('.image-event'); // ✅ classe corrigée

            const imageData = {
                1: {
                    src: "https://placehold.co/600x400?text=Image+1",
                    details: "Monopoly - Un classique des jeux de société."
                },
                2: {
                    src: "https://placehold.co/600x400?text=Image+2",
                    details: "Uno - Jeu de cartes rapide et fun."
                },
                3: {
                    src: "https://placehold.co/600x400?text=Image+3",
                    details: "Échecs - Stratégie et réflexion."
                },
                4: {
                    src: "https://placehold.co/600x400?text=Image+3",
                    details: "Puzzle."
                },
                5: {
                    src: "https://placehold.co/600x400?text=Image+3",
                    details: "Pokemon."
                }
            };

            imageCards.forEach(card => {
                card.addEventListener('click', () => {
                    const id = card.getAttribute('data-id');
                    const data = imageData[id];
                    if (data) {
                        modalImg.src = data.src;
                        modalText.innerHTML = `
            <p>${data.details}</p>
            <button class="button-74" role="button">Voir plus</button>
          `;
                        modal.style.display = "block";
                    }
                });
            });

            closeBtn.onclick = () => {
                modal.style.display = "none";
            };

            window.onclick = (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            };
        };
    </script>
    <script>
        function toggleProfil() {
            document.getElementById('profilDropdown').classList.toggle('hidden');
        }

        document.getElementById('togglePwd').addEventListener('click', () => {
            const pwd = document.querySelector('input[name="mot_de_passe"]');
            const icon = document.querySelector('#togglePwd i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
        document.getElementById('loginForm').addEventListener('submit', e => {
            e.preventDefault();
            const form = e.target, data = new FormData(form);
            fetch('Connexion/traitement-connexion.php', {method: 'POST', body: data})
                .then(r => r.json())
                .then(json => {
                    if (json.success) return window.location = json.redirect;
                    document.getElementById('loginMessage').textContent = json.message;
                })
                .catch(() => document.getElementById('loginMessage').textContent = 'Erreur réseau');
        });
    </script>
    <script>
        function toggleProfil() {
            document.getElementById('profilDropdown').classList.toggle('hidden');
        }

        document.getElementById('togglePwd').addEventListener('click', () => {
            const pwd = document.querySelector('input[name="mot_de_passe"]');
            const icon = document.querySelector('#togglePwd i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
        document.getElementById('loginForm').addEventListener('submit', e => {
            e.preventDefault();
            const form = e.target, data = new FormData(form);
            fetch('Connexion/traitement-connexion.php', {method: 'POST', body: data})
                .then(r => r.json())
                .then(json => {
                    if (json.success) return window.location = json.redirect;
                    document.getElementById('loginMessage').textContent = json.message;
                })
                .catch(() => document.getElementById('loginMessage').textContent = 'Erreur réseau');
        });
    </script>
</body>
</html>
