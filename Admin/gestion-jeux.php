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
    $stmt = $pdo->prepare("DELETE FROM Jeux WHERE id_jeu = ?");
    $stmt->execute([ (int)$_GET['delete'] ]);
    header('Location: gestion-jeux.php');
    exit;
}

// 4) Handle add/edit submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = !empty($_POST['id_jeu'])   ? (int)$_POST['id_jeu']   : null;
    $nom      = $_POST['nom']    ?? '';
    $annee    = (int)($_POST['annee'] ?? 0);
    $genre    = (int)($_POST['genre'] ?? 0);
    $type     = (int)($_POST['type'] ?? 0);
    $court    = $_POST['desc_court'] ?? '';
    $longue   = $_POST['desc_longue'] ?? '';
    $visuel   = $_POST['visuel'] ?? '';

    if ($id) {
        // Update existing
        $stmt = $pdo->prepare("
            UPDATE Jeux
               SET nom=?, annee_sortie=?, id_genre=?, id_type=?, description_courte=?, description_longue=?, visuel_principal=?
             WHERE id_jeu=?
        ");
        $stmt->execute([$nom,$annee,$genre,$type,$court,$longue,$visuel,$id]);
    } else {
        // Insert new
        $stmt = $pdo->prepare("
            INSERT INTO Jeux
              (nom, annee_sortie, id_genre, id_type, description_courte, description_longue, visuel_principal)
            VALUES (?,?,?,?,?,?,?)
        ");
        $stmt->execute([$nom,$annee,$genre,$type,$court,$longue,$visuel]);
    }
    header('Location: gestion-jeux.php');
    exit;
}

// 5) Fetch all genres & types for the form selects
$genres = $pdo->query("SELECT id_genre, libelle FROM Genre ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
$types  = $pdo->query("SELECT id_type, libelle FROM TypeJeu ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);

// 6) Fetch all games
$jeux = $pdo->query("
    SELECT j.id_jeu, j.nom, j.annee_sortie,
           g.libelle AS genre, t.libelle AS type
      FROM Jeux j
 LEFT JOIN Genre g ON j.id_genre = g.id_genre
 LEFT JOIN TypeJeu t ON j.id_type = t.id_type
    ORDER BY j.nom
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Gestion des jeux</title>
    <style>
        body { font-family:Arial,sans-serif; margin:0; padding:0; background:#f4f4f4; }
        header { background:#2c3e50; color:#fff; padding:1em; text-align:center; }
        .container { max-width:1000px; margin:2em auto; padding:0 1em; }
        button { padding:0.5em 1em; border:none; background:#27ae60; color:#fff; border-radius:4px; cursor:pointer; }
        button.delete { background:#c0392b; }
        table { width:100%; border-collapse:collapse; margin-top:1em; background:#fff; }
        th,td { padding:0.75em; border:1px solid #ddd; text-align:left; }
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
    <h1>Administration : Gestion des jeux</h1>
</header>
<div class="container">
    <button onclick="openModal()">+ Ajouter un jeu</button>

    <table>
        <tr>
            <th>ID</th><th>Nom</th><th>Année</th><th>Genre</th><th>Type</th><th>Actions</th>
        </tr>
        <?php foreach($jeux as $j): ?>
            <tr>
                <td><?= $j['id_jeu'] ?></td>
                <td><?= htmlspecialchars($j['nom']) ?></td>
                <td><?= $j['annee_sortie'] ?></td>
                <td><?= htmlspecialchars($j['genre'] ?? '—') ?></td>
                <td><?= htmlspecialchars($j['type'] ?? '—') ?></td>
                <td>
                    <button onclick='editGame(<?= json_encode($j) ?>)'>Modifier</button>
                    <a href="?delete=<?= $j['id_jeu'] ?>" onclick="return confirm('Supprimer ?')">
                        <button class="delete">Supprimer</button>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- Modal form for add/edit -->
<div id="gameModal" class="modal">
    <div class="modal-content">
        <button class="close" onclick="closeModal()">✕</button>
        <h2 id="modalTitle">Ajouter un jeu</h2>
        <form method="post" action="">
            <input type="hidden" name="id_jeu" id="id_jeu">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" required>
            </div>
            <div class="form-group">
                <label for="annee">Année de sortie</label>
                <input type="number" name="annee" id="annee">
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <select name="genre" id="genre">
                    <option value="">— Sélectionner —</option>
                    <?php foreach($genres as $g): ?>
                        <option value="<?= $g['id_genre'] ?>"><?= htmlspecialchars($g['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type">
                    <option value="">— Sélectionner —</option>
                    <?php foreach($types as $t): ?>
                        <option value="<?= $t['id_type'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="desc_court">Description courte</label>
                <textarea name="desc_court" id="desc_court" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="desc_longue">Description longue</label>
                <textarea name="desc_longue" id="desc_longue" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="visuel">URL du visuel</label>
                <input type="text" name="visuel" id="visuel">
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
        document.getElementById('modalTitle').innerText = 'Ajouter un jeu';
        document.querySelector('#gameModal form').reset();
        document.get

