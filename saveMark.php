<?php

require_once 'db.php';
require_once "permCheck.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO marks (user_id, subject, mark, date) VALUES (?, ?, ?, ?)");
    $stmt->execute(array($_POST['user_id'], $_POST['subject'], $_POST['mark'], date('Y-m-d')));

    header("Location: addMark.php");
    exit;
}

?>