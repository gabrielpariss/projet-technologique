<?php
session_start();
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=projet-technologique;charset=utf8',
        'root',
        'root'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Erreur détaillée pour diagnostiquer
    echo json_encode([
        'success' => false,
        'message' => "Erreur de connexion à la base : " . $e->getMessage()
    ]);
    exit;
}

$identifiant   = $_POST['identifiant']   ?? '';
$mot_de_passe  = $_POST['mot_de_passe']  ?? '';

// Vérifie participant
try {
    $stmt = $pdo->prepare(
        "SELECT * FROM Participants WHERE email = ? AND mot_de_passe = ?"
    );
    $stmt->execute([$identifiant, $mot_de_passe]);
    $participant = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Erreur lors de la vérification participant : " . $e->getMessage()
    ]);
    exit;
}

if ($participant) {
    $_SESSION['participant'] = [
        'id'     => $participant['id_participant'],
        'nom'    => $participant['nom'],
        'prenom' => $participant['prenom'],
        'email'  => $participant['email']
    ];
    echo json_encode(['success' => true, 'redirect' => '../index.php']);
    exit;
}

// Vérifie admin
try {
    $stmt = $pdo->prepare(
        "SELECT * FROM Administrateurs WHERE nom_utilisateur = ? AND mot_de_passe = ?"
    );
    $stmt->execute([$identifiant, $mot_de_passe]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Erreur lors de la vérification admin : " . $e->getMessage()
    ]);
    exit;
}

if ($admin) {
    $_SESSION['admin'] = [
        'id'             => $admin['id_admin'],
        'nom_utilisateur'=> $admin['nom_utilisateur']
    ];
    echo json_encode(['success' => true, 'redirect' => '../Admin/tableau-de-bord.php']);
    exit;
}

// Sinon : identifiants invalides
echo json_encode([
    'success' => false,
    'message' => "Identifiant ou mot de passe incorrect."
]);
