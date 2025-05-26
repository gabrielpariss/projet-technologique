<?php
// Connexion à la base de données
try {
    // Ajustez le host/user/mdp si besoin
    $pdo = new PDO(
        'mysql:host=localhost;dbname=projet-technologique;charset=utf8',
        'root',
        'root'
    );
    // Mode exception pour afficher les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En prod, préférez loguer plutôt que d'afficher
    die('Erreur de connexion : ' . $e->getMessage());
}
