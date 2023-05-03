<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;

session_start();

$jwt_secret = "Oluwatobi@24";

//To verify JWT 
function verifyJWT($token)
{
    global $jwt_secret;
    try {
        //Validate JWT format
        if (!preg_match('/^[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]*$/', $token)) {
            throw new Exception('Invalid token format');
        }

        //decode JWT
        // $decoded_token = JWT::decode($token, $jwt_secret, array('HS512'));
        $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
        print_r($decoded_token);

        //Returns the email from the JWT as that was what was used to create the token
        echo $decoded_token->email;
        return $decoded_token->email;
    } catch (Exception $e) {
        echo $e;
        return null;
    }
}




// To get JWT
if( $_SERVER['REQUEST_METHOD'] === 'GET'){
    //Get Token from header
    // $jwt_token = $_SERVER['HTTP_AUTHORIZATION'];
    $jwt_token = "eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCIsImtpZCI6Ik9sdXdhdG9iaUAyNCJ9.eyJlbWFpbCI6InRvYmlAdG9iaS5jb20iLCJleHBpcmVzIjoxNjgzMDM4MjI2fQ.FKIQ7y5u2tdXQUm8NDSnNP1yt-1za48eXLBsbe2ho0__CwUmbuc9eKKfb4_U32WLM3TrQNsYK7ucI7xU3Q1RpQ";
    //Check if user has been authenticated
    $user_email = verifyJWT($jwt_token);
    if($user_email){
        echo json_encode(
            [
                'firstName'=>$_SESSION['firstName'],
                'lastName'=>$_SESSION['lastName'],
                'email'=>$_SESSION['email'],
                'userId'=>$_SESSION['user_id']
            ]
            );
    } else {
        // Invalid JWT token, return error
        http_response_code(401);
        echo json_encode(['error' => 'Invalid JWT token']);
    }
}
?>
