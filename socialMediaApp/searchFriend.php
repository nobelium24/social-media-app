<?php
include "./dbConnect.php";
$connection = new Connection();
$connect = $connection->getConnection();

if(isset($_POST['searchfriend'])){
    $userName = $_POST['userName'];
    $query = "SELECT userName, last_name, first_name FROM `users` WHERE userName = '$userName'";
    $result = mysqli_query($connect,$query);
    $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($fetch);
}

?>