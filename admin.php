<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Administration</title>
    <link rel="stylesheet" href="style/admin.css"/>
</head>
<body>
<header>
    <?php
    require_once "permCheck.php";
    

    $bdd = new PDO('mysql:host=localhost;dbname=progesdb;charset=utf8', 'root', '');
    ?>
</header>

<main>
    <a class="aze" href="newsCreate.php">News</a>
    <a class="aze" href="usersManage.php">Utilisateur</a>
    <a class="aze" href="selectClasses.php">Planning</a>
    <a class="aze" href="addMark.php">Notes</a>
    <a class="aze" href="classesManage.php">Classes</a>
    <a class="aze" href="addSubject.php">Matières</a>
    <a class="aze" href="globalView.php">Vue global</a>
</main>


</body>
</html>