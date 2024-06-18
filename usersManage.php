<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="STYLE/usersManage.css">
</head>
<body>
    <header>
        <?php
        require_once "permCheck.php";

        $bdd = new PDO('mysql:host=localhost;dbname=progesdb;charset=utf8', 'root', '');

        $query = $bdd->prepare("SELECT users.*, classes.name as className FROM users LEFT JOIN classes ON users.class_id = classes.id");
        $query->execute();

        $users = $query->fetchAll();
        ?>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Identifiant</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Rang</th>
                    <th>Classe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['login'] ?></td>
                        <td><?= $user['firstName'] ?></td>
                        <td><?= $user['lastName'] ?></td>
                        <td><?= $user['rank'] ?></td>
                        <td><?= $user['className'] ?></td>
                        <td>
                            <a class="aze" href="editUser.php?id=<?= $user['id'] ?>">Modifier</a>
                            <a class="aze" href="deleteUser.php?id=<?= $user['id'] ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a class="aze" href="createUser.php">Créer un nouvel utilisateur</a>
    </main>
</body>
</html>