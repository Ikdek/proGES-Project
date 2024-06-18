<?php
require_once "permCheck.php";
$bdd = new PDO('mysql:host=localhost;dbname=progesdb;charset=utf8', 'root', '');

if (!empty($_GET['mark_id'])) {
    $mark_id = $_GET['mark_id'];
    $query = $bdd->prepare("SELECT * FROM marks WHERE id = ?");
    $query->execute([$mark_id]);

    if($query->rowCount() > 0){
        $mark = $query->fetch();

        if ($_POST) {
            $new_mark = $_POST['mark'];
            $updateQuery = $bdd->prepare("UPDATE marks SET mark = ? WHERE id = ?");
            $updateQuery->execute([$new_mark, $mark_id]);
            header("Location: globalView.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ProGes - Editer Notes</title>
    <link rel="stylesheet" href="style/editUser.css">
</head>
<body> 
    <?php if(isset($mark)): ?>
    <form id="update_mark_form" action="" method="post">
        <label for="mark">Mark: </label>
        <input type="text" id="mark" name="mark" value="<?= htmlspecialchars($mark['mark'], ENT_QUOTES) ?>" aria-label="Mark" role="textbox">
        <input type="submit" value="Update Mark" aria-label="Update Mark Button" role="button">
    </form>
    <?php else: ?>
    <p id="no_marks_found" tabindex="0" aria-label="No marks found with the given id.">No marks found with the given id.</p>
    <?php endif; ?>
</body>
</html>