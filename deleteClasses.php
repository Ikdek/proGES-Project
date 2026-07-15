<?php
require_once "permCheck.php";
require_once 'db.php';

// Le traitement précède toute sortie HTML, sinon la redirection serait ignorée.
$erreur = "";
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $classId = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");

    if ($stmt->execute([$classId])) {
        header('Location: classesManage.php');
        exit;
    }

    $erreur = "Error deleting class.";
} else {
    $erreur = "No class ID provided. Please go back and try again.";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>ProGes - Suppression Classes</title>
    <link rel="stylesheet" href="STYLE/deleteClasses.css">
</head>

<body>
    <?php require_once "navAdmin.php"; ?>
    <p><?= htmlspecialchars($erreur) ?></p>
</body>
</html>
