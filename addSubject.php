<!DOCTYPE html>
<html>
<head>
    <title>ProGes - Ajouter une matière</title>
    <link rel="stylesheet" href="style/addClasses.css">
</head>
<body>
    <?php
    require_once "db.php";
    require_once "permCheck.php";

    if (isset($_POST['submit'])) {
        $nomDuSujet = isset($_POST['nomDuSujet']) ? trim($_POST['nomDuSujet']) : '';

        if (!empty($nomDuSujet)) {
            $stmt = $conn->prepare("INSERT INTO subject (name) VALUES (?)");
            $stmt->execute([$nomDuSujet]);
            header('Location: globalView.php');
            exit;
        }
    }
    ?>

    <form action="" method="POST">
        <label for="nomDuSujet">Nom de la matiere:</label>
        <input type="text" name="nomDuSujet" id="nomDuSujet" aria-label="Nom du Sujet">
        <button type="submit" name="submit">Ajouter la matière</button>
    </form>
</body>
</html>