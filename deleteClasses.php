<!DOCTYPE html>
<html>

<head>
    <title>ProGes - Suppression Classes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    require_once "permCheck.php";
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            require_once 'db.php';
            $classId = $_GET['id'];
            $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
            $result = $stmt->execute([$classId]);

            if ($result) {
                header('Location: classesManage.php');
                exit;
            } else {
                echo "<p>Error deleting class.</p>";
            }
        } else {
            echo "<p>No class ID provided. Please go back and try again.</p>";
        }
    ?>
</body>
</html>