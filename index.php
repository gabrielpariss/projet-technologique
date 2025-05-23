<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boutique de Jeux</title>
  <link rel="icon" type="image/x-icon" href="Image/Favicon-Logoo.png">
  <link rel="stylesheet" href="style.css">
  <script>
    function myFunction(x) {
        x.classList.toggle("change");
    }
    function toggleMenu() {
        document.getElementById("sideMenu").classList.toggle("hidden");
    }
    function toggleProfil() {
        document.getElementById("profilDropdown").classList.toggle("hidden");
    }
    function openModal(src) {
        const modal = document.getElementById("modal");
        const modalImg = document.getElementById("modalImg");
        modal.style.display = "block";
        modalImg.src = src;
    }
    function closeModal() {
        document.getElementById("modal").style.display = "none";
    }
  </script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      height: 100vh;
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
    }

    .menu-burger {
      font-size: 24px;
      cursor: pointer;
    }

    .menu-icon {
        display: inline-block;
        cursor: pointer;
    }
    
    .bar1, .bar2, .bar3 {
        width: 35px;
        height: 5px;
        background-color: #ccc;
        margin: 6px 0;
        transition: 0.4s;
    }
    
    .change .bar1 {
        transform: translate(0, 11px) rotate(-45deg);
    }

    .change .bar2 {opacity: 0;}

    .change .bar3 {
        transform: translate(0, -11px) rotate(45deg);
    }


    .right-header {
      display: flex;
      align-items: center;
      gap: 20%;
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
      padding: 5px ;
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
      position: relative;
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
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
      border-color: blueviolet;
      padding-bottom: 40px;
    }

    .container h2 {
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
      color: #ccc;
    }

    .image-game img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }


    .image-grid-eve {
      display: flex;
      gap: 10px;
    }
    
    .image-event {
      position: relative;
      width: 150px;
      height: 150px;
      overflow: hidden;
      cursor: pointer;
      color: #ccc;
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

    /* Modal style */
    #modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color:black(0,0,0,0.9);
    }

    #modal img {
      margin: auto;
      display: block;
      max-width: 90%;
      max-height: 90%;
      margin-top: 5%;
    }

    #modal:after {
      content: "✖";
      position: absolute;
      top: 20px;
      right: 40px;
      color: white;
      font-size: 30px;
      cursor: pointer;
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
  
  .footer-right {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 20px;
  }
  
  .social-icons a img {
    height: 16px;
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
  <header>
    <div class="menu-burger" onclick="toggleMenu()">
    <div class="menu-icon" onclick="myFunction(this)">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    </div>

    <h1>Boutique de Jeux</h1>
    <div class="right-header">
      <a href="#">Nouveautés</a>
      <a href="#">Favoris</a>
      <input type="text" placeholder="Rechercher...">
    </div>
      <div class="profil-client">
        <div class="profil-menu" onclick="toggleProfil()"><img src="Image/Favicon-Logoo.png" alt="Avatar" class="avatar"></div>
        <div id="profilDropdown" class="dropdown hidden">
          <a href="#">Mon Profil</a>
          <a href="#">Mes Favoris</a>
          <a href="#">Nouveautés</a>
          <a href="#">Mes Inscriptions</a>
        </div>
    </div>
  </header>

  <nav id="sideMenu" class="hidden">
    <a href="catalogue.php">Catalogue</a>
    <a href="detail_jeu.php">Détail d’un jeu</a>
    <a href="evenements.php">Événements</a>
    <a href="detail_evenement.php">Détail d’un événement</a>
    <a href="inscription_evenement.php">Inscription événement</a>
  </nav>

  <main>
    <div class="container">
      <div class="game-container">
        <h2>Les 5 derniers jeux</h2>
        <div class="image-grid-jeux">
          <div class="image-game"><img src="Image/Logo-Boutique.png"><div class="overlay">Monopoly</div></div>
          <div class="image-game"><img src="Image/Logo-Boutique.png"><div class="overlay">Uno</div></div>
          <div class="image-game"><img src="Image/Logo-Boutique.png"><div class="overlay">Echec</div></div>
          <div class="image-game"><img src="Image/Logo-Boutique.png"><div class="overlay">Puzzle</div></div>
          <div class="image-game"><img src="Image/Logo-Boutique.png"><div class="overlay">Pokemon</div></div>
        </div>
      </div>
      
      <div class="event-container">
        <h2>Les 5 derniers événements</h2>
        <div class="image-grid-eve">
          <div class="image-event"><img src="Image/Logo-Boutique.png">Monopoly</div>
          <div class="image-event"><img src="Image/Logo-Boutique.png">Uno</div>
          <div class="image-event"><img src="Image/Logo-Boutique.png">Echec</div>
          <div class="image-event"><img src="Image/Logo-Boutique.png">Puzzle</div>
          <div class="image-event"><img src="Image/Logo-Boutique.png">Pokemon</div>
        </div>
      </div>
    </div>


    <footer class="footer">
      <div class="footer-left">
        <img src="Logo-Boutique.png" alt="Logo École du Rhône" class="footer-logo">
        <span>&copy; 2018 - 2019 - Tous droits réservés.</span>
      </div>
      <div class="footer-right">
        <div class="social-icons">
          <a href="#"><img src="facebook-icon.png" alt="Facebook" /></a>
          <a href="#"><img src="youtube-icon.png" alt="YouTube" /></a>
          <a href="#"><img src="twitter-icon.png" alt="Twitter" /></a>
          <a href="#"><img src="instagram-icon.png" alt="Instagram" /></a>
        </div>
        <div class="footer-links">
          <a href="#">Endorsements</a>
          <a href="#">FAQ</a>
          <a href="#">Contact</a>
          <a href="#">Mentions légales</a>
        </div>
      </div>
    </footer>
        
  </main>

  <div id="modal" onclick="closeModal()">
    <img id="modalImg" src="" alt="modal image">
  </div>

</body>
</html>
