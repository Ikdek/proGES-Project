<?php
require_once "permCheck.php";

require_once __DIR__ . '/db.php';


if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "DELETE FROM Users WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);

    $stmt->execute();
    
    header("Location: usersManage.php");
    exit;

} else {
    echo "User ID not provided!";
}   

?>