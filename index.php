<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <?php
    session_start();
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

    if (empty ($_SESSION)) {
        header("Location: login.php");
    }

    ?>

    <main>
        <nav class='nav'>
            <div class='logo'>ProGES</div>
            <ul class='menu'>
                <li><a href='index.php'>accueil</a></li>
                <li><a href='plannings.php'>plannings</a></li>
                <li><a href='adminNews.php'>uploadNews</a></li>
                <li><a href='deconnexion.php'>déconnexion</a></li>
            </ul>
            <button class='hamburger'>
                <span class='icon'></span>
            </button>
        </nav>
        <h2>News</h2>
        <?php
        $donnees = file("newsData.txt");
        $donnees = array_reverse($donnees);
        ?>
        <div class="scrollmenu">
            <div class='newsCards'>
                <?php

                foreach ($donnees as $ligne) {
                    $info = explode("|", $ligne);
                    ?>
                    <div class='tpn_card'>
                        <?php
                        echo "<h5>" . $info[0] . "</h5>";
                        echo "<img src='" . $info[2] . "' alt='" . $info[0] . "' style='max-height: 300px;'><br>";
                        echo "<p class='description'>" . $info[1] . "</p>";
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        ?>
        <div class='bgImg'></div>

    </main>

    <script src="navbar.js"></script>


</body>

</html>