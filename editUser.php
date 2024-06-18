<!DOCTYPE html>
<html>

<head>
    <title>ProGes - Editer Profils</title>
    <link rel="stylesheet" href="STYLE/editUser.css">
</head>

<body>
    <?php
    require_once "permCheck.php";
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'progesdb';
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $user_id = intval($_GET['id']);
        $query = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindValue(':id', $user_id, PDO::PARAM_INT);
        $query->execute();
        $user = $query->fetch();

        $classesQuery = $conn->prepare("SELECT * FROM classes");
        $classesQuery->execute();
        $classes = $classesQuery->fetchAll();
    }

    if (isset($_POST['submit'])) {
        if (isset($user)) {
            $user_id = $user['id'];
            $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
            $role = isset($_POST['role']) ? trim($_POST['role']) : '';
            $classId = isset($_POST['class']) ? trim($_POST['class']) : '';

            if (!empty($firstName) && !empty($lastName) && !empty($role) && !empty($classId)) {
                $query = $conn->prepare("UPDATE users SET `firstName` = :firstName, `lastName` = :lastName, `rank` = :role, `class_id` = :class_id WHERE `id` = :id");
                $query->bindValue(':id', $user_id, PDO::PARAM_INT);
                $query->bindValue(':firstName', $firstName, PDO::PARAM_STR);
                $query->bindValue(':lastName', $lastName, PDO::PARAM_STR);
                $query->bindValue(':role', $role, PDO::PARAM_STR);
                $query->bindValue(':class_id', $classId, PDO::PARAM_INT);
                $query->execute();

                header('Location: usersManage.php');
                exit;
            }
        }
    }
    ?>

    <form action="" method="post">
        <label for="firstName">Prénom:</label>
        <input type="text" name="firstName" id="firstName" aria-label="Prénom"
            value="<?= htmlspecialchars($user['firstName'] ?? '') ?>">

        <label for="lastName">Nom:</label>
        <input type="text" name="lastName" id="lastName" aria-label="Nom"
            value="<?= htmlspecialchars($user['lastName'] ?? '') ?>">

        <label for="role">Rôle:</label>
        <select name="role" id="role" aria-label="Rôle Utilisateur">
            <option value="admin" <?= isset($user['rank']) && $user['rank'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="utilisateur" <?= isset($user['rank']) && $user['rank'] == 'utilisateur' ? 'selected' : '' ?>>
                utilisateur</option>
        </select>

        <label for="class">Classe:</label>
        <select name="class" id="class" aria-label="Classe Utilisateur">
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['id'] ?>" <?= isset($user['class_id']) && $user['class_id'] === $class['id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <br>
        <input type="submit" name="submit" value="Mettre à jour l'utilisateur">
    </form>

    <?php
    if (isset($error)) {
        ?>
        <p style="color: red;">
            <?= htmlspecialchars($error) ?>
        </p>
        <?php
    }
    ?>
</body>

</html>