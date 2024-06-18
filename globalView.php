<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProGes - Utilisateurs et Notes</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="STYLE/usersManage.css">
</head>

<body>
    <header>
        <?php
        require_once 'db.php';
        require_once "permCheck.php";
        if (empty($_SESSION) || $_SESSION['rank'] != 'admin') {
            header("Location: login.php");
        }

        $bdd = new PDO('mysql:host=localhost;dbname=progesdb;charset=utf8', 'root', '');

        $filterClassName = isset($_GET["classFilter"]) ? $_GET["classFilter"] : "";
        $filterSubjectName = isset($_GET["subjectFilter"]) ? $_GET["subjectFilter"] : "";
        $filterMarkMin = isset($_GET["markMinFilter"]) ? $_GET["markMinFilter"] : 0;   
        $filterMarkMax = isset($_GET["markMaxFilter"]) ? $_GET["markMaxFilter"] : 20; 
        $sortOrder = isset($_GET["sortOrder"]) ? $_GET["sortOrder"] : "className";

        $classQuery = $bdd->prepare("SELECT name as className FROM classes");
        $classQuery->execute();
        $classes = $classQuery->fetchAll();

        $subjectQuery = $bdd->prepare("SELECT name as subjectName FROM subject");
        $subjectQuery->execute();
        $subjects = $subjectQuery->fetchAll();

        $query = $bdd->prepare(
            "SELECT users.*, classes.name as className, marks.mark, marks.id as markId, subject.name as subjectName
            FROM users
            LEFT JOIN classes ON users.class_id = classes.id
            LEFT JOIN marks ON users.id = marks.user_id
            LEFT JOIN subject ON marks.subject = subject.id
            WHERE classes.name like :className AND
                  subject.name like :subjectName AND
                  marks.mark between :markMin and :markMax
            ORDER BY {$sortOrder}"
        );

        $query->bindValue(':className', '%' . $filterClassName . '%');
        $query->bindValue(':subjectName', '%' . $filterSubjectName . '%');
        $query->bindValue(':markMin', $filterMarkMin, PDO::PARAM_INT);
        $query->bindValue(':markMax', $filterMarkMax, PDO::PARAM_INT);

        $query->execute();

        $usersMarks = $query->fetchAll();
        ?>
    </header>

    <main>
        <form method="get" action="">
            <label for="classFilter">Filtre par classe:</label>
            <select id="classFilter" name="classFilter">
                <option value="">Tout</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['className'] ?>" <?= $filterClassName == $class['className'] ? 'selected' : '' ?>>
                        <?= $class['className'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="subjectFilter">Filtre par sujet:</label>
            <select id="subjectFilter" name="subjectFilter">
                <option value="">Tout</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= $subject['subjectName'] ?>" <?= $filterSubjectName == $subject['subjectName'] ? 'selected' : '' ?>>
                        <?= $subject['subjectName'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="markMinFilter">Filtre par note min:</label>
            <input type="number" id="markMinFilter" name="markMinFilter" min="0" max="20" value="<?= $filterMarkMin ?>">

            <label for="markMaxFilter">Filtre par note max:</label>
            <input type="number" id="markMaxFilter" name="markMaxFilter" min="0" max="20" value="<?= $filterMarkMax ?>">

            <label for="sortOrder">Trier par:</label>
            <select id="sortOrder" name="sortOrder">
                <option value="className" <?= $sortOrder == 'className' ? 'selected' : '' ?>>Nom de la classe</option>
                <option value="firstName" <?= $sortOrder == 'firstName' ? 'selected' : '' ?>>Prénom</option>
                <option value="lastName" <?= $sortOrder == 'lastName' ? 'selected' : '' ?>>Nom de famille</option>
                <option value="mark" <?= $sortOrder == 'mark' ? 'selected' : '' ?>>Note Croissante</option>
            </select>

            <input class="aze" type="submit" value="Appliquer">
        </form>

        <br>

        <table>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom De Famille</th>
                    <th>Classes</th>
                    <th>Matières</th>
                    <th>Notes</th>
                    <th>Editer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usersMarks as $userMark): ?>
                    <tr>
                        <td>
                            <?= $userMark['firstName'] ?>
                        </td>
                        <td>
                            <?= $userMark['lastName'] ?>
                        </td>
                        <td>
                            <?= $userMark['className'] ?>
                        </td>
                        <td>
                            <?= $userMark['subjectName'] ?>
                        </td>
                        <td>
                            <?= $userMark['mark'] ?>
                        </td>
                        <td>
                            <a href="editMark.php?mark_id=<?= $userMark['markId'] ?>" class="aze">Editer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>