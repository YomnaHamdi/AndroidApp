<?php
include_once 'db_connection.php';
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

$headers = getallheaders();
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    list($jwt) = sscanf($authHeader, 'Bearer %s');

    if ($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            $Company_id = $decoded->Company_id; 

            
            $data = json_decode(file_get_contents("php://input"), true);

            
            if (
                isset($data['Job_title']) &&
                isset($data['Job_description']) &&
                isset($data['Employment_type']) &&
                isset($data['Job_location']) &&
                isset($data['Salary_range']) &&
                isset($data['Requirements']) &&
                isset($data['Job_type']) &&
                isset($data['job_mode'])
            ) {
                
                $Job_title = $data['Job_title'];
                $Job_description = $data['Job_description'];
                $Employment_type = $data['Employment_type'];
                $Job_location = $data['Job_location'];
                $Salary_range = $data['Salary_range'];
                $Requirements = $data['Requirements'];
                $Job_type = $data['Job_type'];
                $job_mode = $data['job_mode'];

                
                $stmt = $con->prepare("INSERT INTO jobs (Company_id, Job_title, Job_description, Employment_type, Job_location, Salary_range, Requirements, Job_type, Posted_at, job_mode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
                
                if ($stmt) {
                    $stmt->bind_param("issssssis", $Company_id, $Job_title, $Job_description, $Employment_type, $Job_location, $Salary_range, $Requirements, $Job_type, $job_mode);
                    
                    if ($stmt->execute()) {
                        echo json_encode(array("message" => "Job created successfully."));
                    } else {
                        echo json_encode(array("message" => "Error: " . $stmt->error));
                    }
                } else {
                    echo json_encode(array("message" => "Failed to prepare the SQL statement."));
                }
            } else {
                echo json_encode(array("message" => "Invalid input"));
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
