<?php
require_once "permCheck.php";
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'progesdb';
$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);

function getClasses($conn) {
    $stmt = $conn->prepare("SELECT id, name FROM classes");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['create_user'])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $password = htmlspecialchars(trim($_POST['password']));
    $password_repeat = htmlspecialchars(trim($_POST['password_repeat']));
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $classId = isset($_POST['classId']) ? htmlspecialchars(trim($_POST['classId'])) : null;

    if ($password !== $password_repeat) {
        $password_error = 'Les mots de passe ne correspondent pas.';
    }

    if (strlen($password) < 6) {
        $password_error = 'Le mot de passe doit contenir au moins 6 caractères.';
    }

    if (empty($login) || is_numeric($login) || strlen($login) < 3) {
        $login_error = 'Nom d\'utilisateur invalide.';
    }

    if (empty($login_error) && empty($password_error)) {
        createUser($conn, $login, $password, $firstName, $lastName, $classId);
        header('Location: usersManage.php?msg=Utilisateur créé avec succès!');
    }
}

function createUser($conn, $login, $password, $firstName, $lastName, $classId) {
    $hashed_login = hash('sha256', $login);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (login, password, firstName, lastName, class_id) VALUES (?,?,?,?,?)");
    $stmt->execute([$hashed_login, $hashed_password, $firstName, $lastName, $classId]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Create Users</title>
    <link rel="stylesheet" href="style/addClasses.css">
</head>
<body>
<form action="createUser.php" method="post">
    <label for="login">Nom d'Utilisateur:</label>
    <input type="text" name="login" id="login" value="<?php echo isset($login) ? $login : ''; ?>" required>
    <span class="error-message">
        <?php echo isset($login_error) ? $login_error : ''; ?>
    </span>
    <br>
    <label for="firstName">Prénom:</label>
    <input type="text" name="firstName" id="firstName" value="<?php echo isset($firstName) ? $firstName : ''; ?>" required>
    <br>
    <label for="lastName">Nom:</label>
    <input type="text" name="lastName" id="lastName" value="<?php echo isset($lastName) ? $lastName : ''; ?>" required>
    <br>
    <label for="password">Mot de Passe:</label>
    <input type="password" name="password" id="password" required>
    <span class="error-message">
        <?php echo isset($password_error) ? $password_error : ''; ?>
    </span>
    <br>
    <label for="password_repeat">Répéter le Mot de Passe:</label>
    <input type="password" name="password_repeat" id="password_repeat" required>
    <br>
    <label for="classId">Class:</label>
    <select name="classId" id="classId" required>
        <?php 
        $classes = getClasses($conn);
        foreach ($classes as $class) {
            echo "<option value='{$class['id']}'>{$class['name']}</option>";
        }
        ?>
    </select>
    <br>
    <button type="submit" name="create_user">Créer Utilisateur</button>
    <br><br>
</form>
</body>
</html>