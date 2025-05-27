<?php
session_start();

$_SESSION = [];
session_destroy();
header('Location: /projet-technologique/index.php');
exit;
?>