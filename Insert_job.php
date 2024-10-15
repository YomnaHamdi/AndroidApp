<?php
include_once 'db_connection.php';
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

$data = json_decode(file_get_contents("php://input"), true);


error_log(print_r($data, true)); 


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

    
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if ($jwt) {
            try {
                
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                $company_id = $decoded->data->Company_id; 

    
                error_log(print_r($decoded, true));

                
                $sql_insert = "INSERT INTO jobs (Job_title, Job_description, Employment_type, Job_location, Salary_range, Requirements, Posted_at, Job_type, job_mode, Company_id) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";

                $stmt = $con->prepare($sql_insert);
                $stmt->bind_param("ssssssssi", $Job_title, $Job_description, $Employment_type, $Job_location, $Salary_range, $Requirements, $Job_type, $job_mode, $company_id);

                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Job added successfully"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error adding job: " . $stmt->error]);
                }
                $stmt->close();
            } catch (Exception $e) {
                echo json_encode(['error' => 'Invalid token or unauthorized access']);
            }
        } else {
            echo json_encode(['error' => 'Authorization token not found']);
        }
    } else {
        echo json_encode(['error' => 'Authorization header is missing']);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

mysqli_close($con);
?>
