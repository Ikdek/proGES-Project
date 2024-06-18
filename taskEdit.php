<?php
require_once 'db.php';
require_once "permCheck.php";

$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
$date = isset($_GET['day']) ? $_GET['day'] : null;
$hour = isset($_GET['hour']) ? $_GET['hour'] : null;
$new = isset($_GET['new']) ? $_GET['new'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskDescription = $_POST['taskDescription'];
    $startHour = sprintf("%02d", $hour) . ":00:00";
    $endHour = sprintf("%02d", ($hour + 1) % 24) . ":00:00";
    // Convert the date to the format expected by MySQL
    $date_formated = date('Y-m-d', strtotime($date));

    $sql = "INSERT INTO planning (task, day, startHour, endHour, class_id) values (:task, :day, :startHour, :endHour, :class_id)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'task' => $taskDescription,
        'day' => $date_formated,  
        'startHour' => $startHour,
        'endHour' => $endHour,
        'class_id' => $class_id
    ]);
    header("Location: planningManager.php?class_id=$class_id.php");;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Ajouter Une heure</title>
    <link rel="stylesheet" href="style/editUser.css">
</head>

<body>
    <h2>Add Task</h2>
    <form method="POST">
        <label for="taskDescription">Task Description</label><br>
        <input type="text" id="taskDescription" name="taskDescription" required><br>
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        <input type="hidden" name="date" value="<?php echo $date; ?>">
        <input type="hidden" name="hour" value="<?php echo $hour; ?>">
        <input type="submit" value="Submit"/>
    </form>
</body>
</html>