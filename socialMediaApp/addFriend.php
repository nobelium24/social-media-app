<?php
include "./dbConnect.php";
$connection = new Connection();
$connect = $connection->getConnection();
session_start();

if(isset($_POST['addFriend'])){
    $friendUserName = $_POST['userName'];
    // $friendUserName = "tobito";
    $query1 = "SELECT user_id FROM users WHERE userName = '$friendUserName'";
    $result = mysqli_query($connect,$query1);
    $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $friend_id = $fetch[0]['user_id'];
    $user_id = $_SESSION['user_id'];
    // $user_id = '3';
    $query2 = $connect->prepare('INSERT INTO friends(user_id, friend_id) VALUES(?, ?)');
    $query2->bind_param("ss", $user_id, $friend_id);
    $success = $query2->execute();
    print_r($success);
}
?>