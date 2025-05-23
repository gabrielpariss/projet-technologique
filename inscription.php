<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> Inscription </title>
</head>
<body>

// <?php

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$nom = htmlspecialchars($_POST['nom']);
    //$prenom = htmlspecialchars($_POST['prenom']);
   // $email = htmlspecialchars($_

   // $sujet = "Confirmation de votre inscription";
   // $message = "Bonjour $prenom $nom,\n\nMerci pour votre inscription !\n\nL'administrateur vous contactera par email pour confirmer votre participation à la soirée jeux.\n\nÀ bientôt !";
   // $headers = "From: soiree-jeux@example.com";

    //if (mail($email, $sujet, $message, $headers)) {
     //   echo "<script>
           // alert('Un email de confirmation a été envoyé.');
            // window.location.href = 'mailto:evenement@email.com?subject=Inscription%20Soirée%20Jeux&body=Bonjour,%20je%20souhaite%20confirmer%20mon%20inscription.%20Nom:%20$nom%20Prénom:%20$prenom%20Email:%20$email';
        //</script>";
   // } else {
      //  echo "<p style='color:red;'>Erreur lors de l'envoi du mail.</p>";
  //  }
//}
?>


<pre> <?php ?></pre>
<form action="" method="post">
    <h1 style="text-align:center; font-size: 2.5em; margin-bottom: 30px;">Formulaire d'inscription – Evènement </h1>

    <div>
        <label for="idnom" > nom:</label>
        <input type="text" name="nom" id="idnom" required>
    </div>

    <div>
        <label for="idprenom"> prénom:</label>
        <input type="text" name="prenom" id="idprenom" required>
    </div>

    <div>
        <label for="idemail"> Email:</label>
        <input type="text" name="email" id="idemail" required>
    </div>

    <div>
        <label for="idaccompa"> Nombre d'accompagnants:</label>
        <input type="number" name="accompa" id="idaccompa" min="0" max="10" required>
    </div>

    <div>
        <label> Préférences de jeu:</label>

<ul>
            <li><input type="checkbox" class="checkbox-limit" name="radiog" id="jeu1" value="monopoli"><label for="jeu1">monopoli</label></li>
            <li><input type="checkbox" class="checkbox-limit" name="radiog" id="jeu2" value="chess"><label for="jeu2">chess</label></li>
            <li> <input type="checkbox" class="checkbox-limit" name="radiog" id="jeu3" value="uno"><label for="jeu3">uno</label></li>
            <li><input type="checkbox" class="checkbox-limit" name="radiog" id="jeu4" value="ludo"><label for="jeu4">ludo</label></li>
            <li> <input type="checkbox" class="checkbox-limit" name="radiog" id="jeu5" value="poker"><label for="jeu5">poker</label></li>

</ul>


    </div>
    <input type="submit" value="S'inscrire">
</form>

    <script>
        const  checkboxes = document.querySelectorAll('.checkbox-limit');
        const maxchecked=3;
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const checkedCount = document.querySelectorAll('.checkbox-limit:checked').length;
                if (checkedCount > maxchecked) {
                    checkbox.checked = false;
                    alert(`Tu ne peux sélectionner que ${maxchecked} options.`);
                }
            });
        })

    </script>
</body>
</html>
<?php
