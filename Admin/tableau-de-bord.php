<?php
session_start();
// Protection d'accès
if (!isset($_SESSION['admin'])) {
    header("Location: ../Connexion/Se-connecter.php");
    exit;
}

// Connexion PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=projet-technologique;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des statistiques
$nbJeux = (int)$pdo->query("SELECT COUNT(*) FROM Jeux")->fetchColumn();
$nbEvenements = (int)$pdo->query("SELECT COUNT(*) FROM Evenements")->fetchColumn();
$nbParticipants = (int)$pdo->query("SELECT COUNT(*) FROM Participants")->fetchColumn();
$nbInscriptions = (int)$pdo->query("SELECT COUNT(*) FROM Inscriptions")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <style>
        /* Reset & font */
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
        body { display:flex; min-height:100vh; }
        /* Sidebar */
        nav.sidebar { width:250px; background:#2c3e50; color:white; padding-top:20px; position:fixed; height:100%; }
        nav.sidebar h2 { text-align:center; margin-bottom:30px; font-size:1.2em; }
        nav.sidebar ul { list-style:none; }
        nav.sidebar ul li { margin:10px 0; }
        nav.sidebar ul li a { color:#ecf0f1; text-decoration:none; padding:10px 20px; display:block; transition:0.2s; }
        nav.sidebar ul li a:hover { background:#34495e; }
        /* Main content */
        .main { margin-left:250px; padding:20px; flex:1; background:#ecf0f1; }
        header { display:flex; justify-content:space-between; align-items:center; }
        header h1 { font-size:1.8em; color:#2c3e50; }
        /* Cards */
        .cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-top:20px; }
        .card { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
        .card h3 { font-size:1em; color:#7f8c8d; margin-bottom:10px; }
        .card p { font-size:2.2em; color:#2c3e50; }
        /* Responsive */
        @media(max-width:600px){
            nav.sidebar { position:relative; width:100%; height:auto; }
            .main { margin:0; }
        }
    </style>
</head>
<body>
<nav class="sidebar">
    <h2>Admin</h2>
    <ul>
        <li><a href="gestion-jeux.php">Gérer les jeux</a></li>
        <li><a href="gestion-evenements.php">Gérer les événements</a></li>
        <li><a href="gestion-inscriptions.php">Gérer les inscriptions</a></li>
        <li><a href="../Connexion/deconnexion-admin.php">Se déconnecter</a></li>
    </ul>
</nav>

<div class="main">
    <header>
        <h1>Tableau de bord</h1>
        <span>Bienvenue, <?= htmlspecialchars($_SESSION['admin']['nom_utilisateur']) ?></span>
    </header>

    <div class="cards">
        <div class="card">
            <h3>Nombre de jeux</h3>
            <p><?= $nbJeux ?></p>
        </div>
        <div class="card">
            <h3>Nombre d'événements</h3>
            <p><?= $nbEvenements ?></p>
        </div>
        <div class="card">
            <h3>Nombre de participants</h3>
            <p><?= $nbParticipants ?></p>
        </div>
        <div class="card">
            <h3>Nombre d'inscriptions</h3>
            <p><?= $nbInscriptions ?></p>
        </div>
    </div>
</div>
</body>
</html>



