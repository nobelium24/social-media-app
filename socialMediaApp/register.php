<?php
include('dbConnect.php');
$connection = new Connection();
$conn = $connection->getConnection();

//Get data from frontend
$data = json_decode(file_get_contents('php://input'), true);

//Validate the data
if (!isset($data['firstName']) && !isset($data['lastName']) && !isset($data['email']) && !isset($data['password']) && !isset($data['userName'])) {
    http_response_code(400);
    echo json_encode(["message"=>"Invalid data", "status"=>false]);
    exit;
} 
if(!preg_match("/^[a-zA-Z ]*$/", $data['firstName'])){
    http_response_code(400);
    echo json_encode(["message"=>"Invalid first name format", "status"=>false]);
    exit;
}
if(!preg_match("/^[a-zA-Z ]*$/", $data['lastName'])){
    http_response_code(400);
    echo json_encode(["message"=>"Invalid last name format", "status"=>false]);
    exit;
}
if(!preg_match("/^(?=.*[^\s]).{8,}$/",$data['username'])){
    http_response_code(400);
    echo json_encode(["message"=>"Invalid username format", "status"=>false]);
    exit;
}
if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    echo json_encode(["message"=>"Invalid email format", "status"=>false]);
    exit;
}
if(!preg_match("/^(?=.*[^\s]).{8,}$/",$data['password'])){
    http_response_code(400);
    echo json_encode(["message"=>"Invalid password format", "status"=>false]);
    exit;
}

//Check if email exists
$checkDB = "SELECT * FROM users WHERE email = '$data[email]'";
$result = mysqli_query($conn, $checkDB);
$fetched = mysqli_fetch_all($result, MYSQLI_ASSOC);
if(count($fetched) > 0){
    echo json_encode(["message"=>"email already exists", "status"=>false]);
    exit;
}
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

//Prepare the statement
$insert = $conn->prepare("INSERT INTO users(first_name, last_name, email, password) VALUES(?, ?, ?, ?)");
//Bind the parameters
$insert->bind_param("ssss", $first_name, $last_name, $email, $password);
$success = $insert->execute();
if ($success) {
    http_response_code(201);
    echo json_encode(["message"=>"User created successfully", "status"=>true]);
} else {
    http_response_code(500);
    echo json_encode(["message"=>"Failed to create user", "status"=>false]);
}

?>