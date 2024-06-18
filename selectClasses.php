<?php
    require_once 'db.php';
    require_once "permCheck.php";
    $classes = $conn->query("SELECT id, name FROM classes");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Sélection de la Classe</title>
    <link rel="stylesheet" href="style/editUser.css">
</head>

<body>
    <form action="planningManager.php" method="get">
        <label for="classes">Sélectionnez la classe</label>
        <select id="classes" name="class_id">
            <?php 
                while($class = $classes->fetch()) {
                    echo "<option value='".$class['id']."'>".$class['name']."</option>";
                }
            ?>    
        </select>
        <input type="submit" value="Définir le Planning"/>
    </form>
</body>
</html>