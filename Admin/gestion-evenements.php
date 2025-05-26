<?php
// Admin/gestion-evenements.php
session_start();
// 1) Protection d'accès
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
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

// 3) Suppression d’un événement
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Evenements WHERE id_evenement = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: gestion-evenements.php');
    exit;
}

// 4) Ajout / édition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = !empty($_POST['id_evenement']) ? (int)$_POST['id_evenement'] : null;
    $titre    = trim($_POST['titre'] ?? '');
    $date     = $_POST['date_evenement'] ?? '';
    $duree    = $_POST['duree'] ?? '';
    $capacite = (int)($_POST['capacite'] ?? 0);
    $descr    = trim($_POST['description'] ?? '');

    if ($id) {
        // mise à jour
        $stmt = $pdo->prepare("
            UPDATE Evenements
               SET titre = ?, date_evenement = ?, duree = ?, capacite = ?, description = ?
             WHERE id_evenement = ?
        ");
        $stmt->execute([$titre, $date, $duree, $capacite, $descr, $id]);
    } else {
        // insertion
        $stmt = $pdo->prepare("
            INSERT INTO Evenements (titre, date_evenement, duree, capacite, description)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$titre, $date, $duree, $capacite, $descr]);
    }
    header('Location: gestion-evenements.php');
    exit;
}

// 5) Durées possibles
$durations = ['demi-journée','journée','weekend'];

// 6) Récupération des événements
$events = $pdo->query("
    SELECT id_evenement, titre, date_evenement, duree, capacite, description
      FROM Evenements
     ORDER BY date_evenement DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Gestion des événements</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
        body { display:flex; min-height:100vh; background:#ecf0f1; }

        /* Sidebar identique à gestion-jeux */
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

        /* Contenu principal */
        .admin-main {
            margin-left:200px; padding:20px; flex:1;
        }
        .admin-main header { margin-bottom:1em; }
        .admin-main h1 { font-size:1.8em; color:#2c3e50; }

        .container {
            background:#fff; padding:20px; border-radius:6px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
        }
        .add-btn {
            background:#27ae60; color:#fff; border:none;
            padding:8px 12px; border-radius:4px; cursor:pointer;
            font-size:1em; margin-bottom:12px;
        }

        table {
            width:100%; border-collapse:collapse;
        }
        th, td {
            padding:8px; border:1px solid #ddd; text-align:left; vertical-align:top;
        }
        th { background:#ecf0f1; }
        .modify {
            background:#2980b9; color:#fff; border:none;
            padding:4px 8px; border-radius:4px; cursor:pointer;
        }
        .delete {
            background:#c0392b; color:#fff; border:none;
            padding:4px 8px; border-radius:4px; cursor:pointer;
        }

        /* Modal */
        .modal {
            display:none; position:fixed; top:0; left:0;
            width:100%; height:100%; background:rgba(0,0,0,0.5);
            justify-content:center; align-items:center; z-index:10000;
        }
        .modal.show { display:flex; }
        .modal-content {
            background:#fff; padding:20px; border-radius:6px;
            width:90%; max-width:600px; position:relative; z-index:10001;
        }
        .modal-content h2 { margin-bottom:0.5em; }
        .close {
            position:absolute; top:10px; right:10px;
            background:none; border:none; font-size:1.2em; cursor:pointer;
        }
        .form-group { margin-bottom:1em; }
        .form-group label { display:block; margin-bottom:0.3em; }
        .form-group input, .form-group select, .form-group textarea {
            width:100%; padding:0.5em; border:1px solid #ccc; border-radius:4px;
        }
        .form-actions { text-align:right; margin-top:1em; }
    </style>
</head>
<body>

<nav class="sidebar">
    <h2>Admin</h2>
    <ul>
        <li><a href="tableau-de-bord.php">Tableau de bord</a></li>
        <li><a href="gestion-jeux.php">Gérer les jeux</a></li>
        <li><a href="gestion-evenements.php" class="active">Gérer les événements</a></li>
        <li><a href="gestion-inscriptions.php">Gérer les inscriptions</a></li>
        <li><a href="../Connexion/deconnexion-admin.php">Se déconnecter</a></li>
    </ul>
</nav>

<div class="admin-main">
    <header><h1>Gestion des événements</h1></header>
    <div class="container">
        <button class="add-btn" onclick="openModal()">+ Ajouter un événement</button>

        <table>
            <thead>
            <tr>
                <th>ID</th><th>Titre</th><th>Date</th><th>Durée</th><th>Capacité</th><th>Description</th><th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($events as $e): ?>
                <tr>
                    <td><?= $e['id_evenement'] ?></td>
                    <td><?= htmlspecialchars($e['titre']) ?></td>
                    <td><?= $e['date_evenement'] ?></td>
                    <td><?= $e['duree'] ?></td>
                    <td><?= $e['capacite'] ?></td>
                    <td><?= nl2br(htmlspecialchars($e['description'])) ?></td>
                    <td>
                        <button class="modify" onclick='editEvent(<?= json_encode($e) ?>)'>Modifier</button>
                        <button class="delete"
                                onclick="if(confirm('Supprimer cet événement ?')) location='?delete=<?= $e['id_evenement'] ?>'">
                            Supprimer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="eventModal" class="modal">
    <div class="modal-content">
        <button class="close" onclick="closeModal()">✕</button>
        <h2 id="modalTitle">Ajouter un événement</h2>
        <form method="post" action="">
            <input type="hidden" name="id_evenement" id="id_evenement">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" id="titre" required>
            </div>
            <div class="form-group">
                <label for="date_evenement">Date</label>
                <input type="date" name="date_evenement" id="date_evenement" required>
            </div>
            <div class="form-group">
                <label for="duree">Durée</label>
                <select name="duree" id="duree" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach($durations as $d): ?>
                        <option value="<?= $d ?>"><?= $d ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="capacite">Capacité</label>
                <input type="number" name="capacite" id="capacite" min="1" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" onclick="closeModal()">Annuler</button>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').innerText = 'Ajouter un événement';
        document.querySelector('#eventModal form').reset();
        document.getElementById('id_evenement').value = '';
        document.getElementById('eventModal').classList.add('show');
    }
    function closeModal() {
        document.getElementById('eventModal').classList.remove('show');
    }
    function editEvent(data) {
        document.getElementById('modalTitle').innerText = 'Modifier l\'événement';
        document.getElementById('id_evenement').value   = data.id_evenement;
        document.getElementById('titre').value          = data.titre;
        document.getElementById('date_evenement').value = data.date_evenement;
        document.getElementById('duree').value          = data.duree;
        document.getElementById('capacite').value       = data.capacite;
        document.getElementById('description').value    = data.description;
        document.getElementById('eventModal').classList.add('show');
    }
</script>
</body>
</html>


