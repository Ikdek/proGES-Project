<!DOCTYPE html>
<html>
<head>
    <title>ProGes - Ajouter Une Classe</title>
    <link rel="stylesheet" href="style/addClasses.css">
</head>
<body>
    <?php
    require_once "db.php";
    require_once "permCheck.php";
    
    if (isset($_POST['submit'])) {
        $className = isset($_POST['className']) ? trim($_POST['className']) : '';

        if (!empty($className)) {
            $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
            $stmt->execute([$className]);
            header('Location: classesManage.php');
            exit;
        }
    }
    ?>

    <form action="" method="POST">
        <label for="className">Nom de la classe :</label>
        <input type="text" name="className" id="className" aria-label="Nom de la classe">
        <button type="submit" name="submit">Ajouter une classe</button>
    </form>

</body>
</html>