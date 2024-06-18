<?php
require_once 'db.php';
require_once "permCheck.php";

$weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$weekdays_fr = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
$hours = range(8, 19);

$week = isset($_GET['week']) ? $_GET['week'] : date('W');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

$sql = "SELECT *, DATE_FORMAT(day, '%W') as weekday FROM planning WHERE WEEKOFYEAR(day) = :week AND YEAR(day) = :year AND class_id = :class_id ORDER BY day, startHour";
$stmt = $conn->prepare($sql);
$stmt->execute(['class_id' => $class_id, 'week' => $week, 'year' => $year]);

$plannings = [];

$weekdays_mapping = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'];

while ($row = $stmt->fetch()) {
    $plannings[$weekdays_mapping[$row['weekday']]][date('G', strtotime($row['startHour']))][] = $row;
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

$dates = [];
foreach ($weekdays as $index => $day) {
    $date = new DateTime();
    $date->setISODate($year, $week);
    $date->modify("this week $day");
    $dates[$weekdays_fr[$index]] = $date->format('d-m-Y');
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Modifier le Planning</title>
    <link rel="stylesheet" href="style/Plannings.css">
</head>

<body>
    <div class="week-nav">
        <a class="aze" href="?week=<?= $prevWeek ?>&year=<?= $prevYear ?>&class_id=<?= $class_id ?>">Semaine
            précédente</a>
        <a class="aze" href="?week=<?= $nextWeek ?>&year=<?= $nextYear ?>&class_id=<?= $class_id ?>">Semaine
            suivante</a>
    </div>  
    <table>
        <thead>
            <th>Heure / Jour</th>
            <?php foreach ($weekdays_fr as $day): ?>
                <th>
                    <?php echo $day; ?>
                </th>
            <?php endforeach; ?>
        </thead>
        <tbody>
            <?php foreach ($hours as $hour): ?>
                <tr>
                    <td>
                        <?php echo $hour . ':00 - ' . ($hour + 1) . ':00'; ?>
                    </td>
                    <?php foreach ($weekdays_fr as $day): ?>
                        <td class="<?php echo (isset($plannings[$day][$hour]) ? 'tache' : 'libre'); ?>">
                            <?php $dayEng = array_search($day, $weekdays_fr); ?>
                            <button
                                onclick="window.location.href='taskEdit.php?class_id=<?php echo $class_id; ?>&day=<?php echo DATE('Y-m-d', strtotime("this week {$weekdays[$dayEng]}")); ?>&hour=<?php echo $hour; ?>&new=true'">
                                <?php
                                if (isset($plannings[$day][$hour])) {
                                    foreach ($plannings[$day][$hour] as $event) {
                                        echo '<p class="azer">' . $event['task'] . '</p>';
                                    }
                                } else {
                                    echo "<p class='aze'>Ajouter une Tâche</p>";
                                }
                                ?>
                            </button>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Date</td>
                <?php foreach ($weekdays_fr as $day): ?>
                    <td>
                        <?php echo $dates[$day]; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        </tfoot>
    </table>
</body>

</html>