<?php
include("dbConnect.php");
require_once __DIR__ . '/vendor/autoload.php';
include('CORSheader.php');
cors();
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
use Firebase\JWT\JWT;
$connect = new Connection();
$connection = $connect->getConnection();
$data = json_decode(file_get_contents('php://input'), true);
session_start();

// print_r ($data);

try {
    if(!isset($data['email']) && !isset($data['password'])){
        echo json_encode(["message"=>"Invalid input"]);
        exit;
    }
    
    if(!preg_match("/^(?=.*[^\s]).{8,}$/", $data['password'])){
        http_response_code(400);
        echo json_encode(["message"=>"Password is invalid"]);
        exit;
    }
    
    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode(["message"=>"Invalid email"]);
        exit;
    }
    
    // $email2 = "tobi@tobi.com";
    // $password = "tobi";
    
    // $JWT_SECRET = getenv('JWT_SECRET');
    $JWT_SECRET = "Oluwatobi@24";
    $jwt_key_id = "Oluwatobi@24";
    
    
    // $verify = "SELECT * FROM users WHERE email = '$email2'"; 
    
    // $verify = "SELECT * FROM users WHERE email = '$data[email]'";
    // $query1 = mysqli_query($connection, $verify);
    // $result = mysqli_fetch_all($query1, MYSQLI_ASSOC);
    
    $checkDB = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($checkDB);
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $fetched = $stmt->get_result();
    $result = $fetched->fetch_all(MYSQLI_ASSOC);
    
    if($result){
        $dataPassword = $result[0]['password'];
    
        // $verifyPassword = password_verify($password, $dataPassword);   
        $verifyPassword = password_verify($data['password'], $dataPassword);
    
        if($verifyPassword){
            $name = $result[0]['first_name'];
            $lastName = $result[0]['last_name'];
            $id = $result[0]['user_id'];
            $email = $result[0]['email'];
            $userName = $result[0]['userName'];
    
            $_SESSION['firstName'] = $name;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['userName'] = $userName;
    
            function generateToken($email){
                global $JWT_SECRET, $jwt_key_id;
                $header = [
                    'alg' => 'HS512',
                    'typ' => 'JWT',
                    // 'kid' => $jwt_key_id
                ];
                $payload = [
                    'email' => $email, 
                    'expires'=> time() + 86400
                ];
                $jwt_token = JWT::encode($payload, $JWT_SECRET, 'HS512');
                return $jwt_token;
            }
            $token = generateToken($email);
            http_response_code(200);
            echo json_encode(["message"=>"Welcome $name", "token"=>"$token", "status"=>true, "user_id"=>$id, ]);
        }else{
            http_response_code(400);
            echo json_encode(["message"=>"Invalid password", "status"=>false]);
            exit;
        }
    }else{
        http_response_code(404);
        echo json_encode(["message"=>"Account doesn't exist. Kindly create an account with us", "status"=>false]);
        exit;
    }
} catch (\Throwable $th) {
    echo $th;
    http_response_code(500);
    echo json_encode(["message"=>"Internal server error", "status"=>false]);
    return null;
}
?>