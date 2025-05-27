<?php

session_start();
$message = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
    if($email){
        $message = "Si cet e-mail existe, un lien vous sera envoyé.";
    } else {
        $message = "Veuillez saisir une adresse e-mail valide.";
    }
}
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f2f2f2;display:flex;
            justify-content:center;align-items:center;height:100vh;margin:0;background-color: #ff6600;}
        .box{background:#fff;padding:2em;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.1);width:320px;}
        input{width:100%;padding:.6em;margin-top:.5em;border:1px solid #ccc;border-radius:4px;}
        button{width:100%;padding:.7em;margin-top:1em;background:#3498db;color:#fff;
            border:none;border-radius:4px;cursor:pointer;}
        .msg{margin-top:.8em;color:#c0392b;font-size:.9em;text-align:center;}
        a{display:block;text-align:center;margin-top:1em;color:#2980b9;text-decoration:none;}
    </style>
</head><body>
<div class="box">
    <h2>Réinitialiser le mot de passe</h2>
    <form method="post">
        <label for="email">Votre e-mail</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Envoyer le lien</button>
    </form>
    <?php if($message): ?><div class="msg"><?=htmlspecialchars($message)?></div><?php endif;?>
    <a href="../index.php">← Retour</a>
</div>
</body></html>

