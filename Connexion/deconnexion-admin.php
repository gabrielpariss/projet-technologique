<?php
session_start();
// Supprime toutes les variables de session
$_SESSION = [];
// Détruit la session côté serveur
session_destroy();
// Redirection vers la page d’accueil
header('Location: /projet-technologique/index.php');
exit;
?>