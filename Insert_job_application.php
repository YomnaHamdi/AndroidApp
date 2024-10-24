<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5"; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(["error" => "Token is required."]);
        exit();
    }

    try {
       
        $token = str_replace("Bearer ", "", $token);
    
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));  
        $userId = $decoded->user_id; 

   
        $data = json_decode(file_get_contents("php://input"));
        $jobId = $data->job_id ?? null;

        if (!$jobId) {
            echo json_encode(["error" => "Job ID is required."]);
            exit();
        }

        
        $sql_insert = "INSERT INTO job_application (User_id, job_id) VALUES (?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $userId, $jobId); 

        if ($stmt_insert->execute()) {
            echo json_encode(["message" => "Application submitted successfully."]);
        } else {
            echo json_encode(["error" => "Error submitting application."]);
        }

    } catch (Exception $e) {
        // إذا حدث خطأ في فك التوكن
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
