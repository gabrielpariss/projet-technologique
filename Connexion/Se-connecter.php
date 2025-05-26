<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        #message {
            margin-top: 15px;
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Connexion</h2>
    <form id="loginForm">
        <label for="identifiant">Email ou Nom d'utilisateur</label>
        <input type="text" id="identifiant" name="identifiant" required>

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <button type="submit">Se connecter</button>
        <div id="message"></div>
    </form>
</div>

<script>
    const form = document.getElementById('loginForm');
    const messageBox = document.getElementById('message');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('traitement-connexion.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    messageBox.textContent = data.message;
                }
            })
            .catch(() => {
                messageBox.textContent = "Une erreur est survenue.";
            });
    });
</script>
</body>
</html>


