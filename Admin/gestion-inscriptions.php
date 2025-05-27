<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
    exit;
}

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

if (isset($_GET['validate'])) {
    $stmt = $pdo->prepare("
        UPDATE Inscriptions
           SET id_statut = (SELECT id_statut FROM StatutInscription WHERE libelle = 'Confirmé')
         WHERE id_inscription = ?
    ");
    $stmt->execute([(int)$_GET['validate']]);
    header('Location: gestion-inscriptions.php');
    exit;
}
if (isset($_GET['refuse'])) {
    $stmt = $pdo->prepare("
        UPDATE Inscriptions
           SET id_statut = (SELECT id_statut FROM StatutInscription WHERE libelle = 'Annulé')
         WHERE id_inscription = ?
    ");
    $stmt->execute([(int)$_GET['refuse']]);
    header('Location: gestion-inscriptions.php');
    exit;
}

$sql = "
    SELECT
      i.id_inscription,
      p.nom, p.prenom, p.email, p.nb_accompagnants,
      e.titre       AS evenement,
      s.libelle     AS statut,
      GROUP_CONCAT(j.nom SEPARATOR ', ') AS favoris
    FROM Inscriptions i
    JOIN Participants p      ON i.id_participant = p.id_participant
    JOIN Evenements e        ON i.id_evenement   = e.id_evenement
    JOIN StatutInscription s ON i.id_statut      = s.id_statut
    LEFT JOIN FavorisJeux fj ON i.id_inscription = fj.id_inscription
    LEFT JOIN Jeux j         ON fj.id_jeu        = j.id_jeu
    GROUP BY i.id_inscription
    ORDER BY e.date_evenement DESC, p.nom
";
$inscriptions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Gestion des inscriptions</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
        body { display:flex; min-height:100vh; background:#ecf0f1; }

        /* Sidebar */
        nav.sidebar {
            width:200px; background:#2c3e50; color:#fff;
            position:fixed; top:0; bottom:0; padding-top:20px;
        }
        nav.sidebar h2 {
            text-align:center; margin-bottom:1em; font-size:1.4em;
        }
        nav.sidebar ul { list-style:none; padding:0; }
        nav.sidebar li { margin:10px 0; }
        nav.sidebar a {
            display:block; color:#ecf0f1; text-decoration:none;
            padding:8px 20px; transition:0.2s;
        }
        nav.sidebar a:hover,
        nav.sidebar a.active {
            background:#34495e;
        }

        /* Main content */
        .admin-main {
            margin-left:200px; padding:20px; flex:1;
        }
        .admin-main header { margin-bottom:1em; }
        .admin-main h1 { font-size:1.8em; color:#2c3e50; }

        .container {
            background:#fff; padding:20px; border-radius:6px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
        }
        table {
            width:100%; border-collapse:collapse; margin-top:1em;
        }
        th, td {
            padding:8px; border:1px solid #ddd; text-align:left; vertical-align:top;
        }
        th { background:#ecf0f1; }
        .btn-validate {
            background:#27ae60; color:#fff; border:none;
            padding:4px 8px; border-radius:4px; cursor:pointer;
        }
        .btn-refuse {
            background:#c0392b; color:#fff; border:none;
            padding:4px 8px; border-radius:4px; cursor:pointer;
        }

        /* Responsive table on small screens */
        @media(max-width:800px) {
            table, thead, tbody, th, td, tr { display:block; }
            thead tr { position:absolute; top:-9999px; left:-9999px; }
            tr { margin-bottom:1em; }
            td {
                border:none; position:relative; padding-left:50%;
            }
            td:before {
                position:absolute; top:0; left:0; width:45%; padding:8px;
                white-space:nowrap; font-weight:bold;
            }
            td:nth-of-type(1):before { content:"ID"; }
            td:nth-of-type(2):before { content:"Participant"; }
            td:nth-of-type(3):before { content:"Email"; }
            td:nth-of-type(4):before { content:"Accompagnants"; }
            td:nth-of-type(5):before { content:"Événement"; }
            td:nth-of-type(6):before { content:"Favoris"; }
            td:nth-of-type(7):before { content:"Statut"; }
            td:nth-of-type(8):before { content:"Actions"; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <h2>Admin</h2>
    <ul>
        <li><a href="tableau-de-bord.php">Tableau de bord</a></li>
        <li><a href="gestion-jeux.php">Gérer les jeux</a></li>
        <li><a href="gestion-evenements.php">Gérer les événements</a></li>
        <li><a href="gestion-inscriptions.php" class="active">Gérer les inscriptions</a></li>
        <li><a href="../Connexion/deconnexion-admin.php">Se déconnecter</a></li>
    </ul>
</nav>

<div class="admin-main">
    <header>
        <h1>Gestion des inscriptions</h1>
    </header>
    <div class="container">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Participant</th>
                <th>Email</th>
                <th>Accompagnants</th>
                <th>Événement</th>
                <th>Favoris</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($inscriptions as $i): ?>
                <tr>
                    <td><?= $i['id_inscription'] ?></td>
                    <td><?= htmlspecialchars($i['nom'].' '.$i['prenom']) ?></td>
                    <td><?= htmlspecialchars($i['email']) ?></td>
                    <td><?= $i['nb_accompagnants'] ?></td>
                    <td><?= htmlspecialchars($i['evenement']) ?></td>
                    <td><?= htmlspecialchars($i['favoris'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($i['statut']) ?></td>
                    <td>
                        <?php if ($i['statut'] !== 'Confirmé'): ?>
                            <a href="?validate=<?= $i['id_inscription'] ?>"
                               onclick="return confirm('Valider cette inscription ?')">
                                <button class="btn-validate">Valider</button>
                            </a>
                        <?php endif; ?>
                        <?php if ($i['statut'] !== 'Annulé'): ?>
                            <a href="?refuse=<?= $i['id_inscription'] ?>"
                               onclick="return confirm('Refuser cette inscription ?')">
                                <button class="btn-refuse">Refuser</button>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

