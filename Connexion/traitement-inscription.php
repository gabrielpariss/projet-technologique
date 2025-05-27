<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=projet-technologique;charset=utf8','root','root');
// Récupère $_POST['id_evenement'], nom, prénom, email et jeux[]
// Insère dans Participants (ou retrouve l’existant)
// Insère dans Inscriptions + FavorisJeux
// Redirige ou renvoie un message de confirmation
?>
