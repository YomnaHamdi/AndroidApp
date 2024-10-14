<?php
include_once 'db_connection.php';
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 


$data = json_decode(file_get_contents("php://input"), true);

$headers = getallheaders();
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    list($jwt) = sscanf($authHeader, 'Bearer %s');

    if ($jwt) {
        try {
            // فك تشفير التوكن
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            $company_id = $decoded->Company_id; 

            if (isset($data['job_title']) && isset($data['job_description'])) {
                $job_title = $data['job_title'];
                $job_description = $data['job_description'];

               
                $sql_insert = "INSERT INTO jobs (Job_title, Job_description, Company_id) 
                VALUES ('$job_title', '$job_description', '$company_id')";

                if (mysqli_query($con, $sql_insert)) {
                    echo json_encode(["status" => "success", "message" => "Job added successfully"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error adding job: " . mysqli_error($con)]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid input"]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Invalid token or unauthorized access']);
        }
    } else {
        echo json_encode(['error' => 'Authorization token not found']);
    }
} else {
    echo json_encode(['error' => 'Authorization header is missing']);
}

mysqli_close($con);
?>
