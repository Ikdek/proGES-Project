<?php
session_start();

include 'users.php';

function verifierConnexion($pseudo, $password)
{
  global $myUsers;
  foreach ($myUsers as $user) {
    if ($user['pseudo'] === $pseudo && $user['password'] === $password) {
      $_SESSION['connected'] = true;
      $_SESSION['pseudo'] = $pseudo;
      return true;
    }
  }
  return false;
}

function missMatch()
{
  return "display=block";
};

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['login-username']) && isset($_POST['login-passwd'])) {
    ?>
    <script>
      var submitButton = document.querySelector("#btn-submit");
      submitButton.classList.add("loading");
    </script>
    <?php
    $pseudo = $_POST['login-username'];
    $password = $_POST['login-passwd'];
    if (verifierConnexion($pseudo, $password)) {
      ?>
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          var submitButton = document.querySelector("#btn-submit");
          var submitButtonText = document.querySelector("#btn-submit .button-text");
          setTimeout(function () {
            submitButton.classList.remove("loading");
            submitButton.classList.add("success");
            submitButtonText.innerHTML = "Connexion Réussie";
            setTimeout(function () {
              <?php 
                header('location: index.php')
                ?>
            }, 1000);
          }, 1000);
        });
      </script>
      <?php 
    } else {
      $erreur = "Identifiant ou mot de passe incorrect.";
      echo $erreur;
    }
  }
}

if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>ProGES</title>
</head>

<body>
  <div class="container">
    <div class="well">
      <form method="post">
        <hggroup>
          <h1>Bienvenue, sur ProGES</h1>
          <h2>Connectez-vous à votre compte.</h2>
          <p hidden <?php if (verifierConnexion($pseudo, $password)!= true) missMatch();?>>Identifiant ou Mot De Passe Incorrecte.</p>
        </hggroup>
        <div>
          <input type="text" name="login-username" id="login-username" required>
          <label for="login-username">Identifiant</label>
        </div>

        <div>
          <input type="password" name="login-passwd" id="login-passwd" required>
          <label for="login-passwd">Mot De Passe</label>
        </div>
        <a href="#" id="forgot-passwd">Mot De Passe oublié</a>
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
</body>

</html>