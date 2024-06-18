<?php
require_once 'nav.php';
require_once 'db.php';
if (!isset($_SESSION["user_id"])) {
    die("Veuillez vous connecter d'abord.");
}
$class_id = $_SESSION["class_id"];

$weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$frenchWeekdays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
$hours = range(8, 19);

$week = isset($_GET['week']) ? $_GET['week'] : date('W');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$query = "SELECT *, DATE(day) as date, DATE_FORMAT(day, '%W') as weekday FROM planning WHERE WEEKOFYEAR(day) = :week AND YEAR(day) = :year AND class_id = :class_id ORDER BY day, startHour";
$stmt = $conn->prepare($query);
$stmt->execute(['class_id' => $class_id, 'week' => $week, 'year' => $year]);

$dates = [];
foreach ($weekdays as $day) {
    $date = new DateTime();
    $date->setISODate($year, $week);
    $date->modify("this week $day");
    $dates[$day] = $date->format('Y-m-d');
}
while ($row = $stmt->fetch()) {
    $plannings[$row['weekday']][date('G', strtotime($row['startHour']))][] = $row;
    $dates[$row['weekday']] = $row['date'];
}

$prevWeek = $week - 1;
$nextWeek = $week + 1;
$prevYear = $nextYear = $year;
if ($week == 1) {
    $prevWeek = 52;
    $prevYear = $year - 1;
} elseif ($week == 52) {
    $nextWeek = 1;
    $nextYear = $year + 1;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Planning</title>
    <link rel="stylesheet" href="style/Plannings.css">
</head>

<body>
    <table>
        <thead>
            <th>Heure / Jour</th>
            <?php foreach ($frenchWeekdays as $day): ?>
                <th>
                    <?php echo $day; ?>
                </th>
            <?php endforeach; ?>
        </thead>
        <tfoot>
            <th>Date</th>
            <?php
            foreach ($weekdays as $day) {
                echo "<td>";
                if (isset($dates[$day])) {
                    echo $dates[$day];
                }
                echo "</td>";
            }
            ?>
        </tfoot>
        <tbody>
            <?php foreach ($hours as $hour): ?>
                <tr>
                    <td>
                        <?php echo $hour . ':00 - ' . ($hour + 1) . ':00'; ?>
                    </td>
                    <?php foreach ($weekdays as $day): ?>
                        <td class="<?php echo (isset($plannings[$day][$hour]) ? 'tache' : 'libre'); ?>">
                            <?php
                            if (isset($plannings[$day][$hour])) {
                                foreach ($plannings[$day][$hour] as $event) {
                                    echo '<p>' . $event['task'] . '</p>';
                                }
                            } else {
                                echo "";
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="week-nav">
        <a class="aze" href="?week=<?= $prevWeek ?>&year=<?= $prevYear ?>">Semaine précédente</a>
        <a class="aze" href="?week=<?= $nextWeek ?>&year=<?= $nextYear ?>">Semaine suivante</a>
    </div>
</body>

</html>