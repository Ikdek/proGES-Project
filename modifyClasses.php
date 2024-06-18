<!DOCTYPE html>
<html>

<head>
    <title>ProGes - Modifier la Classe</title>
    <link rel="stylesheet" href="style/modifyClasses.css">
</head>

<body>
    <?php
    require_once "permCheck.php";
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        require_once "db.php";
        $classId = $_GET['id'];
        $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$classId]);
        $class = $stmt->fetch();

        if ($_POST) {
            $newClassName = isset($_POST['className']) ? trim($_POST['className']) : '';

            if (!empty($newClassName)) {
                $stmt = $conn->prepare("UPDATE classes SET name = ? WHERE id = ?");
                $stmt->execute([$newClassName, $classId]);
                header('Location: classesManage.php');
                exit;
            }
        }
    }
    ?>

    <form method="POST">
        <label for="className">Nom de la Classe :</label>
        <input type="text" name="className" id="className" aria-label="Nom de la Classe" value="<?= isset($class) ? htmlspecialchars($class['name']) : '' ?>">
        <button type="submit" name="submit">Modifier la Classe</button>
    </form>

</body>
</html>