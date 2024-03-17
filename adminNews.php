<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $dossier = "MEDIA/newsImg/";
    $fichier = $dossier . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $fichier);
    $donnees = "$titre|$description|$fichier\n";
    file_put_contents("newsData.txt", $donnees, FILE_APPEND);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>
<body>
    <h2>Ajouter une nouvelle image</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Image :</label><br>
        <input type="file" name="image" id="image"><br>
        <label for="titre">Titre :</label><br>
        <input type="text" name="titre" id="titre"><br>
        <label for="description">Description :</label><br>
        <textarea name="description" id="description" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
