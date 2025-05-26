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

// 3) Handle delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Evenements WHERE id_evenement = ?");
    $stmt->execute([ (int)$_GET['delete'] ]);
    header('Location: gestion-evenements.php');
    exit;
}

// 4) Handle add/edit submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = !empty($_POST['id_evenement'])   ? (int)$_POST['id_evenement']   : null;
    $titre    = $_POST['titre']    ?? '';
    $date     = $_POST['date_evenement'] ?? '';
    $duree    = $_POST['duree']    ?? '';
    $capacite = (int)($_POST['capacite'] ?? 0);
    $descr    = $_POST['description'] ?? '';

    if ($id) {
        // Update existing
        $stmt = $pdo->prepare("
            UPDATE Evenements
               SET titre=?, date_evenement=?, duree=?, capacite=?, description=?
             WHERE id_evenement=?
        ");
        $stmt->execute([$titre,$date,$duree,$capacite,$descr,$id]);
    } else {
        // Insert new
        $stmt = $pdo->prepare("
            INSERT INTO Evenements
              (titre, date_evenement, duree, capacite, description)
            VALUES (?,?,?,?,?)
        ");
        $stmt->execute([$titre,$date,$duree,$capacite,$descr]);
    }
    header('Location: gestion-evenements.php');
    exit;
}

// 5) Possible durations
$durations = ['demi-journée','journée','weekend'];

// 6) Fetch all events
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
        body { font-family:Arial,sans-serif; margin:0; padding:0; background:#f4f4f4; }
        header { background:#2c3e50; color:#fff; padding:1em; text-align:center; }
        .container { max-width:1000px; margin:2em auto; padding:0 1em; }
        button { padding:0.5em 1em; border:none; background:#2980b9; color:#fff; border-radius:4px; cursor:pointer; }
        button.delete { background:#c0392b; }
        table { width:100%; border-collapse:collapse; margin-top:1em; background:#fff; }
        th,td { padding:0.75em; border:1px solid #ddd; text-align:left; vertical-align:top; }
        th { background:#ecf0f1; }
        /* Modal */
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; }
        .modal.show { display:flex; }
        .modal-content { background:#fff; padding:1.5em; border-radius:6px; width:90%; max-width:600px; position:relative; }
        .modal-content h2 { margin-top:0; }
        .close { position:absolute; top:0.5em; right:0.5em; background:none; border:none; font-size:1.2em; cursor:pointer; }
        .form-group { margin-bottom:1em; }
        .form-group label { display:block; margin-bottom:0.3em; }
        .form-group input, .form-group select, .form-group textarea {
            width:100%; padding:0.5em; border:1px solid #ccc; border-radius:4px;
        }
        .form-actions { text-align:right; }
    </style>
</head>
<body>

<header>
    <h1>Administration : Gestion des événements</h1>
</header>
<div class="container">
    <button onclick="openModal()">+ Ajouter un événement</button>

    <table>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Date</th>
            <th>Durée</th>
            <th>Capacité</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach($events as $e): ?>
            <tr>
                <td><?= $e['id_evenement'] ?></td>
                <td><?= htmlspecialchars($e['titre']) ?></td>
                <td><?= $e['date_evenement'] ?></td>
                <td><?= $e['duree'] ?></td>
                <td><?= $e['capacite'] ?></td>
                <td><?= nl2br(htmlspecialchars($e['description'])) ?></td>
                <td>
                    <button onclick='editEvent(<?= json_encode($e) ?>)'>Modifier</button>
                    <a href="?delete=<?= $e['id_evenement'] ?>" onclick="return confirm('Supprimer cet événement ?')">
                        <button class="delete">Supprimer</button>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- Modal form for add/edit -->
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
    // Ouvre modal pour création
    function openModal() {
        document.getElementById('modalTitle').innerText = 'Ajouter un événement';
        document.querySelector('#eventModal form').reset();
        document.getElementById('id_evenement').value = '';
        document.getElementById('eventModal').classList.add('show');
    }

    // Ferme modal
    function closeModal() {
        document.getElementById('eventModal').classList.remove('show');
    }

    // Pré-remplit form pour modification
    function editEvent(data) {
        document.getElementById('modalTitle').innerText = 'Modifier l\'événement';
        document.getElementById('id_evenement').value      = data.id_evenement;
        document.getElementById('titre').value             = data.titre;
        document.getElementById('date_evenement').value    = data.date_evenement;
        document.getElementById('duree').value             = data.duree;
        document.getElementById('capacite').value          = data.capacite;
        document.getElementById('description').value        = data.description;
        document.getElementById('eventModal').classList.add('show');
    }
</script>

</body>
</html>

