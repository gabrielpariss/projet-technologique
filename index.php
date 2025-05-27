<!DOCTYPE html>

<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=projet-technologique;charset=utf8',
        'root',
        'root'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur BDD : '.$e->getMessage());
}

// Récupérer les 5 derniers jeux
$games = $pdo
    ->query("SELECT nom, visuel_principal FROM Jeux ORDER BY id_jeu DESC LIMIT 5")
    ->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les 5 derniers événements
$events = $pdo
    ->query("SELECT id_evenement, titre FROM Evenements ORDER BY date_evenement DESC LIMIT 5")
    ->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taverne du Jeux</title>
    <link rel="icon" type="image/x-icon" href="Image/Favicon-Logoo.png">
    <link rel="stylesheet" href="style-index.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-…TON_HASH…" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>

<body>

<!-- Sidebar -->
<nav id="sideMenu" class="sidebar">
    <a href="page_catalogue/indexcataloguejeu.php">Catalogue des jeux</a>
    <a href="/evenement.php">Événements</a>
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
            <a href="page_catalogue/indexcataloguejeu.php">Catalogues des Jeux</a>
            <a href="evenement.php">Événements</a>
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
                <?php foreach ($games as $g): ?>
                    <div class="image-game">
                        <img
                                src="<?= htmlspecialchars($g['visuel_principal']) ?>"
                                alt="<?= htmlspecialchars($g['nom']) ?>"
                        >
                        <div class="overlay"><?= htmlspecialchars($g['nom']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="event-container">
            <h2>Les 5 derniers événements</h2>
            <div class="image-grid-eve">
                <?php foreach ($events as $e): ?>
                    <div class="image-event" data-id="<?= (int)$e['id_evenement'] ?>">
                        <img
                                src="Image/Photo-DefaultEvent.jpg"
                                alt="<?= htmlspecialchars($e['titre']) ?>"
                                class="preview-image"
                        >
                        <div class="overlay"><?= htmlspecialchars($e['titre']) ?></div>
                    </div>
                <?php endforeach; ?>
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
                    src: "Image/Photo-Monopoly.jpg",
                    details: "Monopoly - Un classique des jeux de société. Les joueurs achètent, vendent et gèrent des propriétés pour ruiner leurs adversaires et devenir le plus riche."
                },
                2: {
                    src: "Image/Photo-Uno.jpeg",
                    details: "Uno - Jeu de cartes rapide et coloré où le but est d’être le premier à se débarrasser de toutes ses cartes en jouant des couleurs ou chiffres identiques."
                },
                3: {
                    src: "Image/Photo-Echec.jpeg",
                    details: "Échecs - Stratégie et réflexion. Deux joueurs où le but est de mettre le roi adverse en échec et mat en utilisant différentes pièces aux déplacements spécifiques."
                },
                4: {
                    src: "Image/Photo-Puzzle.webp",
                    details: "Puzzle - Jeu de réflexion consistant à assembler correctement des pièces découpées pour reconstituer une image complète."
                },
                5: {
                    src: "Image/Photo-Pokemon.jpg",
                    details: "Pokemon - Jeu de cartes à collectionner où les joueurs utilisent des Pokémon, des objets et des énergies pour affronter l’adversaire et gagner des cartes Récompense."
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

    <!--Profi Utilisateur-->
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