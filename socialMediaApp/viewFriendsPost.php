<?php
include("dbConnect.php");
include("tokenVerification.php");
$connect = new Connection();
$connectDB = $connect->getConnection();



$headers = getallheaders();
function getFriendsPosts()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        global $headers;
        global $userID;
        global $connectDB;
        try {
            $authorizationHeader = $headers['Authorization'];
            // $jwt_token = explode(' ', $authorizationHeader)[1];
            $getEmail = verifyJWT($authorizationHeader);
            $query1 = "SELECT user_id FROM users WHERE email = '$getEmail'";
            $result1 = mysqli_query($connectDB, $query1);
            $fetch1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);
            $userID = $fetch1[0]['user_id'];
            $query = "SELECT p.*FROM posts p JOIN friends f ON p.user_id = f.friend_id WHERE f.user_id = $userID;";
            $result = mysqli_query($connectDB, $query);
            $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (count($fetch) === 0) {
                echo json_encode(["message" => "No posts found", "status" => false]);
                exit;
            }
            echo json_encode(["message" => "Posts fetched successfully", "status" => true, "data" => $fetch]);

        } catch (\Throwable $th) {
            print_r($th);
            http_response_code(500);
            echo json_encode(["message" => "Internal server error, try again"]);
        }
    }
}
getFriendsPosts();
?>