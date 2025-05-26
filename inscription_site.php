<?php
// inscription_site.php
session_start();
require_once 'Connexion/connexion.php'; // fichier qui crée $pdo
$message = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $nom    = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email  = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
    $pwd    = $_POST['mot_de_passe'] ?? '';
    if($nom && $prenom && $email && $pwd){
        // Vérifier unicité
        $stmt = $pdo->prepare("SELECT 1 FROM Participants WHERE email=?");
        $stmt->execute([$email]);
        if($stmt->fetch()){
            $message = "Cet e-mail est déjà utilisé.";
        } else {
            // Insère (on stocke en clair puisque demandé)
            $stmt = $pdo->prepare(
                "INSERT INTO Participants(nom,prenom,email,mot_de_passe,nb_accompagnants)
               VALUES(?,?,?,?,0)"
            );
            $stmt->execute([$nom,$prenom,$email,$pwd]);
            $_SESSION['participant'] = ['nom'=>$nom,'prenom'=>$prenom,'email'=>$email];
            header('Location: index.php');
            exit;
        }
    } else {
        $message = "Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f2f2f2;display:flex;
            justify-content:center;align-items:center;height:100vh;margin:0;}
        .box{background:#fff;padding:2em;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.1);width:320px;}
        input{width:100%;padding:.6em;margin-top:.5em;border:1px solid #ccc;border-radius:4px;}
        button{width:100%;padding:.7em;margin-top:1em;background:#27ae60;color:#fff;
            border:none;border-radius:4px;cursor:pointer;}
        .msg{margin-top:.8em;color:#c0392b;font-size:.9em;text-align:center;}
        a{display:block;text-align:center;margin-top:1em;color:#2980b9;text-decoration:none;}
    </style>
</head><body>
<div class="box">
    <h2>Créer un compte</h2>
    <form method="post">
        <label>Nom</label>
        <input type="text" name="nom" required>
        <label>Prénom</label>
        <input type="text" name="prenom" required>
        <label>E-mail</label>
        <input type="email" name="email" required>
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required>
        <button type="submit">S’inscrire</button>
    </form>
    <?php if($message): ?><div class="msg"><?=htmlspecialchars($message)?></div><?php endif;?>
    <a href="index.php">← Retour</a>
</div>
</body></html>


