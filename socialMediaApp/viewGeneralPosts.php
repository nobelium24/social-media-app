<?php
include ("dbConnect.php");
include("tokenVerification.php");
$connect = new Connection();
$connectDB = $connect->getConnection();

$headers = getallheaders();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getGeneralPosts();
}
function getGeneralPosts() {
    global $headers;
    global $connectDB;
    $authorizationHeader = $headers['Authorization'];
    // $jwt_token = explode(' ', $authorizationHeader)[1];
    $getEmail = verifyJWT($authorizationHeader);
    $query1 = "SELECT user_id FROM users WHERE email = '$getEmail'";
    $result1 = mysqli_query($connectDB, $query1);
    $fetch1 = mysqli_fetch_assoc($result1);
    if (!$fetch1) {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
        exit;
    }
    global $connectDB;
    try {
        $getPosts = "SELECT * FROM posts";
        $response = mysqli_query($connectDB, $getPosts);
        if(!mysqli_query($connectDB, $getPosts)){
            print_r ("Query error: " + mysqli_error($connectDB));
            http_response_code(500);
            echo json_encode(["message" => "Internal server error, try again"]);
            exit;
        }
        $result = mysqli_fetch_all($response, MYSQLI_ASSOC);
        if (count($result) === 0) {
            http_response_code(200);
            echo json_encode(["message" => "No post from anyone yet"]);
            exit;
        }
        $encodedResult = json_encode($result);
        http_response_code(200);
        echo json_encode(["data" => $encodedResult, "status" => true]);
        exit;
    } catch (\Throwable $th) {
        print_r($th);
        http_response_code(500);
        echo json_encode(["message" => "Internal server error, try again"]);
    }
}
?>