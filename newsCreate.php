<?php
require_once "permCheck.php";
require_once __DIR__ . '/db.php';

// Le traitement précède toute sortie HTML, sinon la redirection serait ignorée.
// Redirection après succès (Post/Redirect/Get) : évite de recréer l'actualité
// si l'utilisateur rafraîchit la page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_FILES['image'] ?? null;
    $titre = trim($_POST['title'] ?? '');
    $contenu = trim($_POST['content'] ?? '');

    if ($image !== null && $image['error'] === UPLOAD_ERR_OK) {
        $dossierUpload = 'MEDIA/newsImg/';
        $nomFichier = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);

        if (move_uploaded_file($image['tmp_name'], __DIR__ . '/' . $dossierUpload . $nomFichier)) {
            $query = $conn->prepare(
                "INSERT INTO news (title, content, img) VALUES (:title, :content, :img)"
            );
            $query->execute([
                ':title' => $titre,
                ':content' => $contenu,
                ':img' => $dossierUpload . $nomFichier,
            ]);

            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Ajouter News</title>
    <link rel="stylesheet" href="STYLE/newsCreate.css">
</head>
<body>
<header>
    <?php require_once "navAdmin.php"; ?>
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
</body>
</html>
