<?php
include "./dbConnect.php";
$connection = new Connection();
$connect = $connection->getConnection();
session_start();
$name = $_SESSION["name"] ?? "Guest";
$id = $_SESSION["id"];

if($_SERVER['REQUEST_METHOD'] === 'GET'){
$id = $_SESSION['id'];
$checkFriends = "SELECT users.* FROM friends
JOIN users ON friends.friend_id = users.id
WHERE friends.user_id = $id";

$getResult = mysqli_query($connect, $checkFriends);
$viewResult = mysqli_fetch_all($getResult, MYSQLI_ASSOC);
echo json_encode($viewResult);
}
?>