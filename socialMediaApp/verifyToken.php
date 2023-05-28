<?php
include("dbConnect.php");
include('CORSheader.php');
include('tokenVerification.php');
cors();
require_once __DIR__ . '/vendor/autoload.php';

$connect = new Connection();
$connectDB = $connect->getConnection();
use Firebase\JWT\JWT;

session_start();

$jwt_secret = "Oluwatobi@24";

//To verify JWT 





// To get JWT
$headers = getallheaders();



function getToken()
{
    global $headers;
    global $jwt_secret;
    global $connectDB;
    try {
        // Access specific header values
        $authorizationHeader = $headers['Authorization'];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //Get Token from header
            // $jwt_token = explode(' ', $authorizationHeader)[1];

            // $jwt_token = "";
            // if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            //     $jwt_token = $matches[1];
            // }
            // echo $jwt_token;

            // $jwt_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJlbWFpbCI6InJhbUBnbWFpbC5jb20iLCJleHBpcmVzIjoxNjg1MTA5NjgyfQ.ixGkbrADpZc30usbPcQMBylW79unQE4UdSpPhH0PJ6E0rrCSQK21b8uAKZueLTfvH4-tvsEv0ZQau04ZrrYs2g";

            // echo $jwt_token;

            //Check if user has been authenticated
            $user_email = verifyJWT($authorizationHeader);
            // echo $user_email;

            $checkDB = "SELECT * FROM users WHERE email = ?";
            $statement = $connectDB->prepare($checkDB);
            $statement->bind_param("s", $user_email);
            $statement->execute();
            $fetched = $statement->get_result();
            $result = $fetched->fetch_assoc();
            // print_r($result, 34);

            // $query = "SELECT * FROM users WHERE email = '$user_email'";
            // $verify = mysqli_query($connectDB, $query);
            // $result = mysqli_fetch_all($verify, MYSQLI_ASSOC);
            print_r($result, 33);
            if (!$result) {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized", "status" => false]);
                exit;
            }
            
            if ($result['email'] !== $user_email) {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized", "status" => false]);
                exit;
            }

            http_response_code(200);
            echo json_encode(["message" => "Authorized", "status" => true, "ram" => 33]);
            return $result['user_id'];

        }
    } catch (\Throwable $th) {
        echo $th, 33;
        http_response_code(500);
        echo json_encode(["message" => "Internal server error", "status" => false]);
        return null;
    }
}

getToken();
?>
