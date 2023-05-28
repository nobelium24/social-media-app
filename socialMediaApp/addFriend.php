<?php
include ("dbConnect.php");
include("tokenVerification.php");

$connect1 = new Connection();
$connect = $connect1->getConnection();
session_start();


$data = json_decode(file_get_contents('php://input'), true);

if(isset($_POST['addFriend'])){
    $friendUserName = $_POST['userName'];
    // $friendUserName = "tobito";
    $query1 = "SELECT user_id FROM users WHERE userName = '$friendUserName'";
    $result = mysqli_query($connect,$query1);
    $fetch = mysqli_fetch_assoc($result);
    $friend_id = $fetch['user_id'];
    $user_id = $data['user_id'];
    // $user_id = '3';
    $query2 = $connect->prepare('INSERT INTO friends(user_id, friend_id) VALUES(?, ?)');
    $query2->bind_param("ss", $user_id, $friend_id);
    $success = $query2->execute();
    print_r($success);
}
?>