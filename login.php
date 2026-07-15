<?php
// La logique doit s'exécuter AVANT toute sortie HTML : sinon session_start()
// ne peut plus envoyer le cookie de session et la connexion ne persiste pas.
session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/src/functions.php';
$bdd = dbConnection();

function verifierConnexion($pseudo, $password)
{
    global $bdd;

    $hashed_pseudo = hashLogin($pseudo); // Même hachage qu'à la création du compte (createUser.php)

    $query = $bdd->prepare("SELECT * FROM users WHERE login = :pseudo");
    $query->bindValue(":pseudo", $hashed_pseudo, PDO::PARAM_STR);  // Lier le pseudo haché
    $query->execute();
    $user = $query->fetch();

    // Vérifier le hachage du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['connected'] = true;
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['rank'] = $user['rank'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['class_id'] = $user['class_id'];
        return true;
    }

    return false;
}

$erreur = "";
$connexionReussie = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login-username']) && isset($_POST['login-passwd'])) {
        if (verifierConnexion($_POST['login-username'], $_POST['login-passwd'])) {
            $connexionReussie = true;
        } else {
            $erreur = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="STYLE/login.css">
  <title>ProGes - Connexion</title>
</head>

<body>
  <div class="container">
    <div class="well">
      <form method="post">
        <hggroup>
          <h1>Bienvenue, sur ProGES</h1>
          <h2>Connectez-vous à votre compte.</h2>
          <?php if (!empty($erreur)) : ?>
            <p><?php echo $erreur; ?></p>
          <?php endif; ?>
        </hggroup>
        <div>
          <input type="text" name="login-username" id="login-username" required>
          <label for="login-username">Identifiant</label>
        </div>

        <div>
          <input type="password" name="login-passwd" id="login-passwd" required>
          <label for="login-passwd">Mot De Passe</label>
        </div>
        <button type="submit" class=button id="btn-submit">
          <span class="button-text">Connexion</span>
          <div class="button-loader">
            <div></div>
            <div></div>
            <div></div>
          </div>
        </button>
      </form>
    </div>

    <img src="MEDIA/LoginPage.jpg">
  </div>

  <?php if ($connexionReussie) : ?>
    <script>
      var submitButton = document.querySelector("#btn-submit");
      var submitButtonText = document.querySelector("#btn-submit .button-text");
      submitButton.classList.add("loading");
      setTimeout(function () {
        submitButton.classList.remove("loading");
        submitButton.classList.add("success");
        submitButtonText.innerHTML = "Connexion Réussie";
        setTimeout(function () {
          window.location.href = "index.php";
        }, 1000);
      }, 1000);
    </script>
  <?php endif; ?>
</body>

</html>
