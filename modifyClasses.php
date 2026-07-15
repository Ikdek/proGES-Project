<?php
require_once "permCheck.php";
require_once "db.php";

// Le traitement précède toute sortie HTML, sinon la redirection serait ignorée.
$class = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $classId = $_GET['id'];
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
<!DOCTYPE html>
<html>

<head>
    <title>ProGes - Modifier la Classe</title>
    <link rel="stylesheet" href="STYLE/modifyClasses.css">
</head>

<body>
    <?php require_once "navAdmin.php"; ?>

    <form method="POST">
        <label for="className">Nom de la Classe :</label>
        <input type="text" name="className" id="className" aria-label="Nom de la Classe" value="<?= !empty($class) ? htmlspecialchars($class['name']) : '' ?>">
        <button type="submit" name="submit">Modifier la Classe</button>
    </form>

</body>
</html>
