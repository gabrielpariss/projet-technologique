<?php

header('Content-Type: application/json');

$host = 'localhost';
$db = 'Projet-Technologique';
$user = 'root';
$pass = 'root';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
}
catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage();
}

print("je suis connecté");

$SQLQuery = "SELECT * FROM jeux";
$SQLStmt = $conn->prepare($SQLQuery);
$SQLStmt->execute();

    $jeux=$SQLStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Jeux</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Catalogue des Jeux</h1>
<div class="games-section" id="games-container">
    <?php foreach ($jeux as $jeu): ?>
        <div class="game-card" onclick="openPopup(<?= $jeu['id_jeu'] ?>)">
            <div class="game-image" style="background-image: url('<?= htmlspecialchars($jeu['visuel_principal']) ?>');"></div>

            <div class="game-info">
                <h3 class="game-title"><?= htmlspecialchars($jeu['nom']) ?></h3>
                <p class="game-description"><?= htmlspecialchars($jeu['description_courte']) ?></p>
            </div>
        </div>

        <div class="modal-overlay" id="modal-<?= $jeu['id_jeu'] ?>">
            <div class="modal-content">
                <button class="modal-close" onclick="closePopup(<?= $jeu['id_jeu'] ?>)">&times;</button>
                <h2 id="modal-title"><?= htmlspecialchars($jeu['nom']) ?></h2>
                <div id="modal-body">
                    <p><strong>Année de sortie :</strong> <?= $jeu['annee_sortie'] ?></p>
                    <p><?= nl2br(htmlspecialchars($jeu['description_longue'])) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

}