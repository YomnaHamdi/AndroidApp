<?php
include_once 'db_connection.php';
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

$data = json_decode(file_get_contents("php://input"), true);

// طباعة البيانات المستلمة لمساعدتك في تتبع الخطأ
error_log(print_r($data, true)); 


if (
    isset($data['Job_title']) &&
    isset($data['Job_description']) &&
    isset($data['salary']) &&
    isset($data['location'])
) {
    
    $Job_title = $data['Job_title'];
    $Job_description = $data['Job_description'];
    $salary = $data['salary'];
    $location = $data['location'];

   
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if ($jwt) {
            try {
              
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                error_log(print_r($decoded, true));
                $company_id = $decoded->data->Company_id; 

                
                $sql_insert = "INSERT INTO jobs (Job_title, Job_description, salary, location, Company_id) 
                VALUES (?, ?, ?, ?, ?)";
                
                
                $stmt = $con->prepare($sql_insert);
                $stmt->bind_param("ssisi", $Job_title, $Job_description, $salary, $location, $company_id);

                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Job added successfully"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error adding job: " . $stmt->error]);
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
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

mysqli_close($con);
?>
