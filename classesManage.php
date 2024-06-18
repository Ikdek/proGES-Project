<!DOCTYPE html>
<html>

<head>
  <title>ProGes - Classes</title>
  <link rel="stylesheet" href="style/classesManage.css">
</head>

<body>
    <?php
        require_once "db.php";
        require_once "permCheck.php";
        $query = $conn->prepare("SELECT * FROM classes");
        $query->execute();
        $classes = $query->fetchAll();
    ?>

    <table>
        <thead>
            <tr>
                <th>Nom de la classe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?= htmlspecialchars($class['name']) ?></td>
                    <td>
                        <a class="aze" href="modifyClasses.php?id=<?= $class['id'] ?>">Modifier</a> 
                        <a class="aze" href="deleteClasses.php?id=<?= $class['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a class="aze" href="addClasses.php">Ajouter une classe</a>

</body>
</html>