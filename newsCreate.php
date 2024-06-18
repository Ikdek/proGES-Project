<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Ajouter News</title>
    <link rel="stylesheet" href="style/newsCreate.css">
</head>
<body>
<header>
    <?php
    require_once "permCheck.php";

    $bdd = new PDO('mysql:host=localhost;dbname=progesdb;charset=utf8', 'root', '');
    ?>
</header>

<main>
    <h1>Ajouter une nouvelle actualité</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Image :</label><br>
        <input type="file" name="image" id="image"><br>
        <label for="title">Titre :</label><br>
        <input type="text" name="title" id="title"><br>
        <label for="content">Description :</label><br>
        <textarea name="content" id="content" rows="4" cols="50"></textarea><br>
        <input class="btnSend" type="submit" value="Envoyer">
    </form>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $img = $_FILES['image'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if ($img['error'] == 0) {
        $uploadDir = "MEDIA/newsImg/";
        $fileName = uniqid() . "." . pathinfo($img['name'], PATHINFO_EXTENSION);

        if (move_uploaded_file($img['tmp_name'], __DIR__ . '/MEDIA/newsImg/' . $fileName)) {
            $query = $bdd->prepare("INSERT INTO news (title, content, img)
                                   VALUES (:title, :content, :img)");
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':content', $content, PDO::PARAM_STR);
            $query->bindValue(':img', $uploadDir . $fileName, PDO::PARAM_STR);
            $query->execute();
        }
    }
}
?>
</body>
</html>