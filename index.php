<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Accueil</title>
    <link rel="stylesheet" href="STYLE/index.css">
</head>

<body>
    <?php
    require_once 'nav.php';
    if (!isset($_SESSION["user_id"])) {
        die("Veuillez vous connecter d'abord.");
    }
    $bdd = new PDO('mysql:host=localhost;dbname=progesdb;charset=utf8', 'root', '');

    function fetchNewsData()
    {
        global $bdd;

        $query = $bdd->query("SELECT * FROM news");
        return $query->fetchAll();
    }

    $newsData = fetchNewsData();
    ?>

    <main>
        <h1>Bienvenue sur ProGes</h1>
        <p>Nous nous engageons à fournir un site RAPIDE a tout nos éléves</p><br><br>

        <h2>Dernières actualitées :</h2>
        <div class="scrollmenu">
            <div class='newsCards'>
                <?php
                $newsData = fetchNewsData();

                foreach ($newsData as $newsItem) { ?>
                    <div class='tpn_card'>
                        <?php
                        echo "<h5>" . $newsItem['title'] . "</h5>";
                        echo "<img src='" . $newsItem['img'] . "' alt='" . $newsItem['title'] . "' style='max-height: 300px;'><br>";
                        echo "<p class='description'>" . $newsItem['content'] . "</p>";
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <h2>À propos de notre école</h2>
        <p>Notre école se consacre à la promotion de l'excellence dans tous les aspects de l'éducation. (Surtout en boisson) </p>

    </main>

    <script src="navbar.js"></script>

</body>

</html>