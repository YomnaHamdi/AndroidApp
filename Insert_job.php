<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';
include 'auth_middleware.php';
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
        $company_id = $decoded->Company_id;  
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"));
    $job_title = $data->job_title ?? null;
    $job_description = $data->job_description ?? null;
    $job_type = $data->job_type ?? null;
    $location = $data->location ?? null;
    $salary = $data->salary ?? null;

    if (!$job_title || !$job_description || !$job_type || !$location || !$salary) {
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    
    $sql = "INSERT INTO job_posts (Company_id, job_title, job_description, job_type, location, salary, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isssss", $company_id, $job_title, $job_description, $job_type, $location, $salary);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Job post added successfully."]);
    } else {
        echo json_encode(["error" => "Error adding job post."]);
    }

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
