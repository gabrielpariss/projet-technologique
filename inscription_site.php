<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=projet-technologique;charset=utf8', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Récupération des données
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';

    // Vérifie si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Participants WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetchColumn() > 0) {
        $message = "❌ Un participant avec cet e-mail est déjà inscrit.";
    } else {
        // Insertion du mot de passe en clair (⚠️ pour test uniquement)
        $stmt = $pdo->prepare("INSERT INTO Participants (nom, prenom, email, mot_de_passe, nb_accompagnants) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe]);
        $message = "✅ Inscription réussie. Vous pouvez maintenant vous connecter ou vous inscrire à un événement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription au site</title>
</head>
<body>
<h1>Inscription au site</h1>

<?php if (!empty($message)) : ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="" method="post">
    <label>Nom :</label>
    <input type="text" name="nom" required><br><br>

    <label>Prénom :</label>
    <input type="text" name="prenom" required><br><br>

    <label>Email :</label>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label>
    <input type="password" name="mot_de_passe" required><br><br>

    <button type="submit">S'inscrire</button>
</form>
</body>
</html>

