<?php
// Admin/gestion-jeux.php
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

// Suppression
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM Jeux WHERE id_jeu = ?")
        ->execute([(int)$_GET['delete']]);
    header('Location: gestion-jeux.php');
    exit;
}

// Ajout / édition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = !empty($_POST['id_jeu']) ? (int)$_POST['id_jeu'] : null;
    $nom      = trim($_POST['nom'] ?? '');
    $annee    = (int)($_POST['annee'] ?? 0);
    $genre    = (int)($_POST['genre'] ?? 0);
    $type     = (int)($_POST['type'] ?? 0);
    $court    = trim($_POST['desc_court'] ?? '');
    $longue   = trim($_POST['desc_longue'] ?? '');
    $visuel   = trim($_POST['visuel'] ?? '');

    if ($id) {
        $stmt = $pdo->prepare("
            UPDATE Jeux
               SET nom             = ?,
                   annee_sortie    = ?,
                   id_genre        = ?,
                   id_type         = ?,
                   description_courte = ?,
                   description_longue = ?,
                   visuel_principal   = ?
             WHERE id_jeu = ?
        ");
        $stmt->execute([$nom,$annee,$genre,$type,$court,$longue,$visuel,$id]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO Jeux
            (nom,annee_sortie,id_genre,id_type,description_courte,description_longue,visuel_principal)
            VALUES (?,?,?,?,?,?,?)
        ");
        $stmt->execute([$nom,$annee,$genre,$type,$court,$longue,$visuel]);
    }
    header('Location: gestion-jeux.php');
    exit;
}

// Chargement données
$genres = $pdo->query("SELECT id_genre, libelle FROM Genre ORDER BY libelle")
    ->fetchAll(PDO::FETCH_ASSOC);
$types  = $pdo->query("SELECT id_type, libelle FROM TypeJeu ORDER BY libelle")
    ->fetchAll(PDO::FETCH_ASSOC);

$jeux = $pdo->query("
    SELECT j.id_jeu, j.nom, j.annee_sortie,
           j.id_genre, j.id_type,
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
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
        body { display:flex; min-height:100vh; background:#ecf0f1; }

        /* Sidebar identique */
        nav.sidebar {
            width:200px; background:#2c3e50; color:#fff;
            position:fixed; top:0; bottom:0; padding-top:20px;
        }
        nav.sidebar h2 {
            text-align:center; margin-bottom:1em; font-size:1.4em;
        }
        nav.sidebar ul { list-style:none; }
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
            padding:8px; border:1px solid #ddd; text-align:left;
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

        /* Modal au-dessus de tout */
        .modal {
            display:none; position:fixed; top:0; left:0;
            width:100%; height:100%; background:rgba(0,0,0,0.5);
            justify-content:center; align-items:center;
            z-index:10000;
        }
        .modal.show { display:flex; }
        .modal-content {
            background:#fff; padding:20px; border-radius:6px;
            width:90%; max-width:600px; position:relative;
            z-index:10001;
        }
        .modal-content h2 { margin-bottom:0.5em; }
        .close {
            position:absolute; top:10px; right:10px;
            background:none; border:none; font-size:1.2em;
            cursor:pointer;
        }
        .form-group { margin-bottom:1em; }
        .form-group label { display:block; margin-bottom:0.3em; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width:100%; padding:0.5em; border:1px solid #ccc;
            border-radius:4px;
        }
        .form-actions { text-align:right; margin-top:1em; }
    </style>
</head>
<body>

<nav class="sidebar">
    <h2>Admin</h2>
    <ul>
        <li><a href="tableau-de-bord.php">Tableau de bord</a></li>
        <li><a href="gestion-jeux.php" class="active">Gérer les jeux</a></li>
        <li><a href="gestion-evenements.php">Gérer les événements</a></li>
        <li><a href="gestion-inscriptions.php">Gérer les inscriptions</a></li>
        <li><a href="../Connexion/deconnexion-admin.php">Se déconnecter</a></li>
    </ul>
</nav>

<div class="admin-main">
    <header><h1>Gestion des jeux</h1></header>
    <div class="container">
        <button class="add-btn" onclick="openModal()">+ Ajouter un jeu</button>
        <table>
            <thead>
            <tr>
                <th>ID</th><th>Nom</th><th>Année</th><th>Genre</th><th>Type</th><th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($jeux as $j): ?>
                <tr>
                    <td><?= $j['id_jeu'] ?></td>
                    <td><?= htmlspecialchars($j['nom']) ?></td>
                    <td><?= $j['annee_sortie'] ?></td>
                    <td><?= htmlspecialchars($j['genre']  ?? '—') ?></td>
                    <td><?= htmlspecialchars($j['type']   ?? '—') ?></td>
                    <td>
                        <button class="modify" onclick='editGame(<?= json_encode($j) ?>)'>Modifier</button>
                        <button class="delete"
                                onclick="if(confirm('Supprimer ce jeu ?')) location='?delete=<?= $j['id_jeu'] ?>'">
                            Supprimer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

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
    const modal = document.getElementById('gameModal');
    function openModal(){
        document.getElementById('modalTitle').innerText = 'Ajouter un jeu';
        document.getElementById('id_jeu').value = '';
        document.querySelector('form[action=""]').reset();
        modal.classList.add('show');
    }
    function closeModal(){
        modal.classList.remove('show');
    }
    function editGame(data){
        document.getElementById('modalTitle').innerText = 'Modifier le jeu #'+data.id_jeu;
        document.getElementById('id_jeu').value      = data.id_jeu;
        document.getElementById('nom').value         = data.nom;
        document.getElementById('annee').value       = data.annee_sortie;
        document.getElementById('genre').value       = data.id_genre;
        document.getElementById('type').value        = data.id_type;
        document.getElementById('desc_court').value  = data.description_courte;
        document.getElementById('desc_longue').value = data.description_longue;
        document.getElementById('visuel').value      = data.visuel_principal;
        modal.classList.add('show');
    }
    modal.addEventListener('click', e=>{
        if(e.target===modal) closeModal();
    });
</script>
</body>
</html>

