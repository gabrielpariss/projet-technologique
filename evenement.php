<?php
// evenement.php
// 1) Connexion
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=projet-technologique;charset=utf8',
        'root',
        'root'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$gamesStmt = $pdo->query("SELECT id_jeu, nom FROM Jeux ORDER BY nom");
$allGames  = $gamesStmt->fetchAll(PDO::FETCH_ASSOC);

// 2) Récupération des événements
$stmt = $pdo->query("
    SELECT id_evenement, titre, date_evenement, description
    FROM Evenements
    ORDER BY date_evenement
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Événements - Jeux de société</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* …| copier ici tout votre CSS exactement sans modifier |… */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Rubik', sans-serif;
            background: #E86E39;
            color: #fff;
            min-height: 100vh;
        }
        h1 {
            text-align: center;
            padding: 2rem 1rem 1rem;
            font-size: 2.5rem;
            margin: 0;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .subtitle {
            text-align: center;
            padding: 0 1rem 2rem;
            font-size: 1.2rem;
            opacity: 0.8;
            margin: 0;
        }
        .events-page { display: block; }
        .events-page.hidden { display: none; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background: linear-gradient(135deg, #3a3f5a, #4a5568);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
            border-color: #4ecdc4;
        }
        .card h2 {
            margin: 1rem 0 0.5rem;
            font-size: 1.8rem;
            color: #fff;
        }
        .card p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .btn-register {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(76, 205, 196, 0.4);
        }
        .btn-register:hover::before {
            left: 100%;
        }
        .info-section {
            background: rgba(58, 63, 90, 0.8);
            margin: 2rem;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .info-section h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #4ecdc4;
        }
        .inscription-page {
            display: none;
            background: white;
            min-height: 100vh;
            padding: 20px;
        }
        .inscription-page.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg,
            #ff6b6b 0%,
            #4ecdc4 20%,
            #45b7d1 40%,
            #96ceb4 60%,
            #feca57 80%,
            #ff9ff3 100%);
        }
        .inscription-page h1 {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 30px;
            color: #2c3e50;
            position: relative;
            background: none;
            -webkit-text-fill-color: #2c3e50;
        }
        .inscription-page h1::after {
            content: '🎲🎮🃏';
            display: block;
            font-size: 0.6em;
            margin-top: 10px;
            opacity: 0.8;
        }
        .back-btn {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(127, 140, 141, 0.4);
        }
        .form-group { margin-bottom: 25px; position: relative; }
        label { display: block; margin-bottom: 8px; color: #34495e; font-weight: bold; text-transform: capitalize; font-size: 1.1em; }
        input[type="text"], input[type="email"], input[type="number"] {
            width: 100%; padding: 15px; border: 3px solid #ecf0f1; border-radius: 12px; font-size: 16px;
            transition: all 0.3s ease; background: #fafafa; box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="number"]:focus {
            outline: none; border-color: #3498db; background: white; box-shadow: 0 0 20px rgba(52,152,219,0.2);
            transform: translateY(-2px);
        }
        .preferences-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px; border-radius: 15px; margin: 25px 0; border: 2px solid #dee2e6;
        }
        .preferences-section label {
            color: #495057; font-size: 1.2em; margin-bottom: 15px;
        }
        .games-list {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .game-item {
            background: white; padding: 15px; border-radius: 12px; border: 2px solid transparent;
            transition: all 0.3s ease; cursor: pointer; position: relative; overflow: hidden;
        }
        .game-item::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; transition: all 0.3s ease;
        }
        .game-item:hover {
            transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .game-item input[type="checkbox"] { display: none; }
        .game-item input[type="checkbox"]:checked + label {
            color: white; font-weight: bold;
        }
        .game-item:has(input:checked) {
            border-color: #3498db; background: linear-gradient(135deg, #3498db, #2980b9);
            color: white; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(52,152,219,0.3);
        }
        .game-item label {
            cursor: pointer; margin: 0; text-transform: capitalize; font-size: 1em;
            display: flex; align-items: center; justify-content: center; height: 100%; min-height: 40px;
        }
        .submit-btn {
            width: 100%; padding: 18px;
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: white; border: none; border-radius: 15px; font-size: 1.3em;
            font-weight: bold; cursor: pointer; transition: all 0.3s ease;
            text-transform: uppercase; letter-spacing: 1px; margin-top: 20px;
            position: relative; overflow: hidden;
        }
        .submit-btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255,107,107,0.4);
        }
        .submit-btn:hover::before { left: 100%; }
        .submit-btn:active { transform: translateY(-1px); }
        /* animations + responsive omitted for brevity… */
    </style>
</head>
<body>
<!-- Page des événements -->
<div class="events-page" id="eventsPage">
    <h1>🎲 Événements - Jeux de société 🎮</h1>
    <p class="subtitle">Rejoignez-nous pour des soirées inoubliables !</p>

    <div class="grid">
        <?php foreach($events as $e): ?>
            <div class="card">
                <div class="badge">📅 <?= htmlspecialchars($e['date_evenement']) ?></div>
                <div class="icon">🎲</div>
                <h2><?= htmlspecialchars($e['titre']) ?></h2>
                <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
                <button
                    class="btn-register"
                    onclick="showInscription(<?= (int)$e['id_evenement'] ?>,
                        '<?= addslashes(htmlspecialchars($e['titre'])) ?>',
                        '<?= htmlspecialchars($e['date_evenement']) ?>')">
                    S'inscrire
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="info-section">
        <h3>ℹ️ Informations pratiques</h3>
        <p>📍 Lieu : Salle communautaire, 123 Rue des Jeux<br>
            🕐 Horaire : 19h00 – 23h00<br>
            🎫 Participation : Gratuite<br>
            🍕 Collations et boissons fournies
        </p>
    </div>
</div>

<!-- Page d'inscription (non modifiée) -->
<div class="inscription-page" id="inscriptionPage">
    <div class="container">
        <button class="back-btn" onclick="showEvents()">← Retour aux événements</button>
        <form id="registrationForm">
            <h1 id="eventTitle">Formulaire d'inscription</h1>
            <div class="form-group">
                <label for="idnom">Nom :</label>
                <input type="text" name="nom" id="idnom" required>
            </div>
            <div class="form-group">
                <label for="idprenom">Prénom :</label>
                <input type="text" name="prenom" id="idprenom" required>
            </div>
            <div class="form-group">
                <label for="idemail">Email :</label>
                <input type="email" name="email" id="idemail" required>
            </div>
            <div class="form-group">
                <label for="idaccompa">Nombre d'accompagnants :</label>
                <input type="number" name="accompa" id="idaccompa" min="0" max="10" value="0" required>
            </div>
            <div class="preferences-section">
                <label>Préférences de jeu :</label>
                <ul class="games-list">
                    <?php foreach ($allGames as $jeu): ?>
                        <li class="game-item" data-game="<?= $jeu['id_jeu'] ?>">
                            <input
                                type="checkbox"
                                class="checkbox-limit"
                                name="radiog[]"
                                id="jeu<?= $jeu['id_jeu'] ?>"
                                value="<?= $jeu['id_jeu'] ?>"
                            >
                            <label for="jeu<?= $jeu['id_jeu'] ?>">
                                <?= htmlspecialchars($jeu['nom']) ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <input type="submit" value="S'inscrire" class="submit-btn">
        </form>
    </div>
</div>

<script>
    let currentGame = '';
    let currentEventName = '';
    let currentEventDate = '';

    function showInscription(id, eventName, eventDate) {
        currentGame      = id;
        currentEventName = eventName;
        currentEventDate = eventDate;
        document.getElementById('eventsPage').classList.add('hidden');
        document.getElementById('inscriptionPage').classList.add('active');
        document.getElementById('eventTitle').textContent = `Inscription – ${eventName}`;
        uncheckAllGames();
        const gameCheckbox = document.querySelector(`input[value="${id}"]`);
        if (gameCheckbox) {
            gameCheckbox.checked = true;
            updateGameItemStyle(gameCheckbox);
        }
    }
    function showEvents() {
        document.getElementById('inscriptionPage').classList.remove('active');
        document.getElementById('eventsPage').classList.remove('hidden');
    }
    function uncheckAllGames() {
        document.querySelectorAll('.checkbox-limit').forEach(cb => {
            cb.checked = false;
            updateGameItemStyle(cb);
        });
    }
    function updateGameItemStyle(cb) {
        const item = cb.closest('.game-item');
        if (cb.checked) {
            item.style.borderColor = '#3498db';
            item.style.background = 'linear-gradient(135deg, #3498db, #2980b9)';
            item.style.color = 'white';
            item.style.transform = 'translateY(-3px)';
            item.style.boxShadow = '0 8px 25px rgba(52,152,219,0.3)';
        } else {
            item.style.borderColor = 'transparent';
            item.style.background = 'white';
            item.style.color = '#495057';
            item.style.transform = 'translateY(0)';
            item.style.boxShadow = 'none';
        }
    }
    document.querySelectorAll('.checkbox-limit').forEach(cb => {
        cb.addEventListener('change', () => {
            const max = 3, checked = document.querySelectorAll('.checkbox-limit:checked').length;
            if (checked > max) {
                cb.checked = false;
                const a = document.createElement('div');
                a.innerHTML = `🎲 Vous ne pouvez sélectionner que ${max} jeux maximum !`;
                a.style.cssText = `
                  position: fixed; top:20px; right:20px;
                  background: linear-gradient(135deg,#ff6b6b,#ee5a24);
                  color:white;padding:15px 25px;border-radius:10px;
                  box-shadow:0 10px 30px rgba(0,0,0,0.3);
                  z-index:1000;animation:slideIn .3s ease;font-weight:bold;
                `;
                document.body.appendChild(a);
                setTimeout(()=>{
                    a.style.animation='slideOut .3s ease forwards';
                    setTimeout(()=>a.remove(),300);
                },2500);
            }
            updateGameItemStyle(cb);
        });
    });
    document.getElementById('registrationForm').addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(e.target);
        const nom  = data.get('nom'), prenom = data.get('prenom'),
            email= data.get('email'), accomp = data.get('accompa'),
            jeux = data.getAll('radiog[]');
        const a = document.createElement('div');
        a.innerHTML = `✅ Merci ${prenom} ${nom} ! Votre inscription pour « ${currentEventName} » le ${currentEventDate} a été enregistrée.`;
        a.style.cssText = `
          position: fixed; top:20px; right:20px;
          background: linear-gradient(135deg,#27ae60,#2ecc71);
          color:white;padding:15px 25px;border-radius:10px;
          box-shadow:0 10px 30px rgba(0,0,0,0.3);
          z-index:1000;animation:slideIn .3s ease;font-weight:bold;
          max-width:300px;
        `;
        document.body.appendChild(a);
        setTimeout(()=>{
            a.style.animation='slideOut .3s ease forwards';
            setTimeout(()=>{
                a.remove();
                showEvents();
                e.target.reset();
                uncheckAllGames();
            },300);
        },2000);
    });
</script>
</body>
</html>
