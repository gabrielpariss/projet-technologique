<?php
// Admin/tableau-de-bord.php
session_start();
// Protection d'accès
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
    exit;
}

// Connexion PDO
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=projet-technologique;charset=utf8',
        'root',
        'root'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Compteur de visites
$visitsFile = __DIR__ . '/visits.txt';
if (!file_exists($visitsFile)) {
    file_put_contents($visitsFile, '0');
}
$nbSiteVisits = (int)file_get_contents($visitsFile) + 1;
file_put_contents($visitsFile, (string)$nbSiteVisits);

// Statuts utiles
$idConfirmé   = (int)$pdo->query("SELECT id_statut FROM StatutInscription WHERE libelle = 'Confirmé'")->fetchColumn();
$idAnnulé     = (int)$pdo->query("SELECT id_statut FROM StatutInscription WHERE libelle = 'Annulé'")->fetchColumn();
$idEnAttente  = (int)$pdo->query("SELECT id_statut FROM StatutInscription WHERE libelle = 'En attente'")->fetchColumn();

// Statistiques
$nbJeux           = (int)$pdo->query("SELECT COUNT(*) FROM Jeux")->fetchColumn();
$nbEvenements     = (int)$pdo->query("SELECT COUNT(*) FROM Evenements")->fetchColumn();
$nbInscriptions   = (int)$pdo->query("SELECT COUNT(*) FROM Inscriptions")->fetchColumn();
$nbPending        = (int)$pdo->query("SELECT COUNT(*) FROM Inscriptions WHERE id_statut = {$idEnAttente}")->fetchColumn();
$nbUpcoming       = (int)$pdo->query("SELECT COUNT(*) FROM Evenements WHERE date_evenement >= CURDATE()")->fetchColumn();
$nbAnnulations    = (int)$pdo->query("SELECT COUNT(*) FROM Inscriptions WHERE id_statut = {$idAnnulé}")->fetchColumn();
// Moyenne participants confirmés par événement
$avgPerEvent      = round(
    $pdo->query("
      SELECT AVG(cnt) FROM (
        SELECT COUNT(*) AS cnt
          FROM Inscriptions
         WHERE id_statut = {$idConfirmé}
         GROUP BY id_evenement
      ) AS sub
    ")->fetchColumn(),
    1
);

// Prochains événements
$stmt = $pdo->prepare("
    SELECT id_evenement, titre, date_evenement
      FROM Evenements
     WHERE date_evenement >= CURDATE()
     ORDER BY date_evenement ASC
     LIMIT 5
");
$stmt->execute();
$nextEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
        body { display:flex; min-height:100vh; background:#ecf0f1; }
        /* Sidebar */
        nav.sidebar { width:200px; background:#2c3e50; color:#fff;
            position:fixed; top:0; bottom:0; padding-top:20px; }
        nav.sidebar h2 { text-align:center; margin-bottom:1em; font-size:1.4em; }
        nav.sidebar ul { list-style:none; }
        nav.sidebar li { margin:10px 0; }
        nav.sidebar a {
            display:block; color:#ecf0f1; text-decoration:none;
            padding:8px 20px; transition:0.2s;
        }
        nav.sidebar a:hover, nav.sidebar a.active { background:#34495e; }
        /* Main content */
        .main { margin-left:200px; padding:20px; flex:1; }
        header { display:flex; justify-content:space-between; align-items:center; }
        header h1 { font-size:1.8em; color:#2c3e50; }
        header span { font-size:1em; color:#7f8c8d; }
        /* Cards grid: 4 columns */
        .cards {
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:20px;
            margin-top:20px;
        }
        .card {
            background:#fff; padding:20px;
            border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);
        }
        .card h3 { font-size:1em; color:#7f8c8d; margin-bottom:10px; }
        .card p { font-size:2.2em; color:#2c3e50; }
        /* Upcoming events */
        .upcoming { margin-top:40px; }
        .upcoming h2 { font-size:1.4em; color:#2c3e50; margin-bottom:10px; }
        .upcoming table { width:100%; border-collapse:collapse; }
        .upcoming th, .upcoming td {
            padding:8px; border:1px solid #ddd; text-align:left;
        }
        .upcoming th { background:#ecf0f1; }
        @media(max-width:800px){
            .cards { grid-template-columns:1fr 1fr; }
        }
        @media(max-width:500px){
            nav.sidebar { width:100%; position:relative; height:auto; }
            .main { margin:0; }
            .cards { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <h2>Admin</h2>
    <ul>
        <li><a href="tableau-de-bord.php" class="active">Tableau de bord</a></li>
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
            <h3>Inscriptions totales</h3>
            <p><?= $nbInscriptions ?></p>
        </div>
        <div class="card">
            <h3>Visites du site</h3>
            <p><?= $nbSiteVisits ?></p>
        </div>
        <div class="card">
            <h3>Événements à venir</h3>
            <p><?= $nbUpcoming ?></p>
        </div>
        <div class="card">
            <h3>Inscriptions en attente</h3>
            <p><?= $nbPending ?></p>
        </div>
        <div class="card">
            <h3>Annulations</h3>
            <p><?= $nbAnnulations ?></p>
        </div>
        <div class="card">
            <h3>Moyenne participants/évén.</h3>
            <p><?= $avgPerEvent ?></p>
        </div>
    </div>

    <section class="upcoming">
        <h2>Suivi des prochains événements</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($nextEvents as $ev): ?>
                <tr>
                    <td><?= $ev['id_evenement'] ?></td>
                    <td><?= htmlspecialchars($ev['titre']) ?></td>
                    <td><?= $ev['date_evenement'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

</body>
</html>




