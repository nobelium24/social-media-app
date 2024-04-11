<?php
include "./dbConnect.php";
include "tokenVerification.php";

$connect1 = new Connection();
$connect = $connect1->getConnection();

require_once(__DIR__ . '/vendor/autoload.php');

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

$cloudinary = new Cloudinary([
        'cloud' => [
        'cloud_name' => 'woleogunba',
        'api_key' => '473735162712444',
        'api_secret' => 'lB6yiWTohmHQDZ4YVQBHveB1TG8',
    ],
]);

// $cloudinary = new Cloudinary([
    //     'cloud' => [
//         'cloud_name' => 'woleogunba',
//         'api_key' => '473735162712444',
//         'api_secret' => 'lB6yiWTohmHQDZ4YVQBHveB1TG8',
//         'url' => [
    //             'secure' => true
//         ]
//     ]
// ]);

// use Aws\S3\S3Client;
// use Aws\Exception\AwsException;
// use Aws\Common\Credentials\Credentials;


$bucketName = 'test-socialmedia-app';
$awsRegion = 'us-east-1';
$accessKey = 'test';
$secretKey = 'test';

$headers = getallheaders();
$data1 = json_decode(file_get_contents('php://input'), true);



try {
    $data = $data1['image'];
    $caption = $data1['caption'];
    $authorizationHeader = $headers['Authorization'];
    $getEmail = verifyJWT($authorizationHeader);
    $query1 = "SELECT user_id FROM users WHERE email = '$getEmail'";
    $result1 = mysqli_query($connect, $query1);
    $fetch1 = mysqli_fetch_assoc($result1);
    $userID = $fetch1['user_id'];

    if (isset($_POST)) {
        $decodedData = base64_decode($data);

        // Create a temporary file to store the image
        $tempFileName = tempnam(sys_get_temp_dir(), 'image');
        $tempFile = fopen($tempFileName, 'wb');
        fwrite($tempFile, $decodedData);
        fclose($tempFile);

        // Upload the temporary file to Cloudinary
        $uploadResult = $cloudinary->uploadApi()->upload($tempFileName, [
            'public_id' => $caption,
            'resource_type' => 'image',
        ]);

        // Delete the temporary file
        unlink($tempFileName);

        $url = $uploadResult['secure_url'];

        $query = $connect->prepare("INSERT INTO posts (user_id, caption, image_url) VALUES (?, ?, ?)");
        $query->bind_param("iss", $userID, $caption, $url);
        $success = $query->execute();
        if ($success) {
            echo json_encode(["message" => "Post uploaded successfully"]);
        } else {
            echo json_encode(["message" => "Post upload failed"]);
        }








        // $credentials = new Credentials($accessKey, $secretKey);

        // $s3 = new S3Client([
        //     'version' => 'latest',
        //     'region' => $awsRegion,
        //     'credentials' => $credentials,
        // ]);

        // // Generate a unique file name
        // $fileName = uniqid('image_') . '.jpg';

        // // Upload the decoded image data to AWS S3
        // $result = $s3->putObject([
        //     'Bucket' => $bucketName,
        //     'Key' => $fileName,
        //     'Body' => $decodedData,
        //     'ContentType' => 'image/jpeg', // Adjust the content type accordingly
        // ]);

        // if ($result) {
        //     $imageUrl = $result['ObjectURL'];
        //     $query = $connect->prepare("INSERT INTO posts (user_id, caption, image_url) VALUES (?, ?, ?)");
        //     $query->bind_param("iss", $userID, $caption, $imageUrl);
        //     $success = $query->execute();
        //     if ($success) {
        //         echo json_encode(["message" => "Post uploaded successfully"]);
        //     } else {
        //         echo json_encode(["message" => "Post upload failed"]);
        //     }
        // } else {
        //     echo json_encode(["message" => "Failed to upload image"]);
        // }

    }
} catch (\Throwable $th) {
    echo $th;
    http_response_code(500);
    echo json_encode(["message" => "Internal server error", "status" => false]);
}
?>