<?php
session_start();
// Supprime toutes les variables de session
$_SESSION = [];
// Détruit la session côté serveur
session_destroy();
// Redirection vers la page de connexion admin
header('Location: Se-connecter.php');
exit;
?>