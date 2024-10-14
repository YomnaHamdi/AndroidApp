<?php
require_once 'db_connection.php';
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
            $company_id = $decoded->data->Company_id; 

            
            if (isset($_GET['Job_id'])) {
                $Job_id = $_GET['Job_id'];
                $stmt = $con->prepare("SELECT * FROM jobs WHERE Job_id = ? AND Company_id = ?");
                $stmt->bind_param("ii", $Job_id, $company_id);
                $stmt->execute();
                $result = $stmt->get_result();
                echo json_encode($result->fetch_assoc());
            } else {
                
                $stmt = $con->prepare("SELECT * FROM jobs WHERE Company_id = ?");
                $stmt->bind_param("i", $company_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $jobs = array();
                while ($row = $result->fetch_assoc()) {
                    $jobs[] = $row;
                }
                echo json_encode($jobs);
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
