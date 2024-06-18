<?php
require_once "db.php";
require_once "permCheck.php";
$users = $conn->prepare("SELECT users.*, classes.name AS className FROM users 
JOIN classes ON users.class_id = classes.id");
$users->execute();
$subjects = $conn->prepare("SELECT * FROM subject");
$subjects->execute();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>ProGes - Ajouter des Notes</title>
    <link rel="stylesheet" href="style/addMark.css">
</head>

<body>
    <form method="POST" action="saveMark.php">
        <label for="user_id">Étudiant:</label>
        <select id="user_id" name="user_id" required>
            <?php while($user = $users->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $user['id'] ?>"><?php echo $user['firstName'], ' ', $user['lastName'], ' - ', $user['className'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="subject">Matière:</label>
        <select id="subject" name="subject" required>
            <?php while($subject = $subjects->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $subject['id'] ?>"><?php echo $subject['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="mark">Note:</label>
        <input type="number" id="mark" name="mark" step="0.01" required>

        <button type="submit">Ajouter Note</button>
    </form>

</body>

</html>