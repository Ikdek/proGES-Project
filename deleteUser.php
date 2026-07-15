<?php
require_once "permCheck.php";

$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'progesdb';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';


$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


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