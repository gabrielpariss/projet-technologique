<?php
session_start();
// 1) Protection d'accès
if (!isset($_SESSION['admin'])) {
    header('Location: ../Connexion/Se-connecter.php');
    exit;
}

// 2) Connexion PDO
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

// 3) Handle validate / refuse actions
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

// 4) Fetch all inscriptions with participant, event, status, favoris
$sql = "
  SELECT
    i.id_inscription,
    p.nom, p.prenom, p.email, p.nb_accompagnants,
    e.titre AS evenement,
    s.libelle AS statut,
    GROUP_CONCAT(j.nom SEPARATOR ', ') AS favoris
  FROM Inscriptions i
  JOIN Participants p        ON i.id_participant = p.id_participant
  JOIN Evenements e          ON i.id_evenement   = e.id_evenement
  JOIN StatutInscription s   ON i.id_statut      = s.id_statut
  LEFT JOIN FavorisJeux fj   ON i.id_inscription = fj.id_inscription
  LEFT JOIN Jeux j           ON fj.id_jeu        = j.id_jeu
  GROUP BY i.id_inscription
  ORDER BY e.date_evenement DESC, p.nom
";
$inscriptions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Gérer les inscriptions</title>
    <style>
        body { font-family:Arial,sans-serif; margin:0; padding:0; background:#f4f4f4; }
        header { background:#2c3e50; color:#fff; padding:1em; text-align:center; }
        .container { max-width:1200px; margin:2em auto; padding:0 1em; }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th,td { padding:0.75em; border:1px solid #ddd; vertical-align:top; }
        th { background:#ecf0f1; }
        button { padding:0.4em 0.8em; border:none; border-radius:4px; cursor:pointer; color:#fff; }
        .btn-validate { background:#27ae60; }
        .btn-refuse   { background:#c0392b; }
        @media(max-width:800px){
            table, thead, tbody, th, td, tr { display:block; }
            th { position:absolute; top:-9999px; left:-9999px; }
            tr { margin:0 0 1em 0; }
            td { border:none; position:relative; padding-left:50%; }
            td:before {
                position:absolute; top:0; left:0; width:45%; padding:0.75em;
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
<header>
    <h1>Administration : Gestion des inscriptions</h1>
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
        <?php foreach ($inscriptions as $insc): ?>
            <tr>
                <td><?= $insc['id_inscription'] ?></td>
                <td><?= htmlspecialchars($insc['nom'] . ' ' . $insc['prenom']) ?></td>
                <td><?= htmlspecialchars($insc['email']) ?></td>
                <td><?= $insc['nb_accompagnants'] ?></td>
                <td><?= htmlspecialchars($insc['evenement']) ?></td>
                <td><?= htmlspecialchars($insc['favoris'] ?? '—') ?></td>
                <td><?= htmlspecialchars($insc['statut']) ?></td>
                <td>
                    <?php if ($insc['statut'] !== 'Confirmé'): ?>
                        <a href="?validate=<?= $insc['id_inscription'] ?>"
                           onclick="return confirm('Valider cette inscription ?')">
                            <button class="btn-validate">Valider</button>
                        </a>
                    <?php endif; ?>
                    <?php if ($insc['statut'] !== 'Annulé'): ?>
                        <a href="?refuse=<?= $insc['id_inscription'] ?>"
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
</body>
</html>

