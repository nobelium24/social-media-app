<?php
include "./dbConnect.php";
$connection = new Connection();
$connect = $connection->getConnection();

require_once(__DIR__ . '/vendor/autoload.php');

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name' => 'woleogunba',
            'api_key'    => '473735162712444',
            'api_secret' => 'lB6yiWTohmHQDZ4YVQBHveB1TG8',
        ],
    ]
);

$data = base64_decode($_POST['image']);
$caption = $_POST['caption'];
$userId = $_SESSION['user_id'];

if (isset($_POST)) {
    $file = base64_encode($data);
    $cloudinary->uploadApi()->upload(
        $file = base64_encode($data), 
        ['public_id' => $caption, 'resource_type' => 'image']
    );
    $url = $cloudinary->image($caption)->resize(Resize::fill(200, 200))->toUrl();
    $query = $connect->prepare("INSERT INTO posts (user_id, caption, image_url) VALUES (?, ?, ?)");
    $query->bind_param("iss", $userId, $caption, $url);
    $success = $query->execute();
    if ($success) {
        echo json_encode(["message" => "Post uploaded successfully"]);
    } else {
        echo json_encode(["message" => "Post upload failed"]);
    }
}
?>