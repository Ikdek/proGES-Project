<?php
require_once "nav.php";
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Veuillez vous connecter d'abord.");
}

$stmt = $conn->prepare("SELECT m.mark, m.date, s.name 
                        FROM marks AS m 
                        INNER JOIN subject AS s 
                        ON m.subject = s.id 
                        WHERE m.user_id = ? 
                        ORDER BY s.name, m.date");
$stmt->execute(array($_SESSION["user_id"]));
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/marks.css">
    <title>ProGes - Notes</title>
</head>

<body>
    <?php 
    $currentSubject = null;
    foreach ($result as $row) {
        if ($row['name'] !== $currentSubject) {
            if ($currentSubject !== null) {
                echo '</table>';
            }
            $currentSubject = $row['name'];
            echo "<h2>{$row['name']}</h2>";
            echo '<table>';
            echo '<tr><th>Note</th><th>Date</th></tr>';
        }
        echo "<tr><td>{$row['mark']}</td><td>{$row['date']}</td></tr>";
    }
    if ($currentSubject !== null) {
        echo '</table>';
    }
    ?>
</body>

</html>