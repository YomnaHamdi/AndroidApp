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
            $user_id = $decoded->data->User_id;

            
            $query = "SELECT * FROM users WHERE User_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $User_id);
            $stmt->execute();
            $user_result = $stmt->get_result()->fetch_assoc();

            
            $query = "SELECT * FROM experience WHERE User_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $User_id);
            $stmt->execute();
            $experience_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            
            $query = "SELECT * FROM certificate WHERE User_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $User_id);
            $stmt->execute();
            $certificate_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            
            $query = "SELECT * FROM skills WHERE User_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $User_id);
            $stmt->execute();
            $skills_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            
            $response = [
                'user_info' => $user_result,
                'experience' => $experience_result,
                'certificate' => $certificate_result,
                'skills' => $skills_result,
            ];

        
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Invalid token or unauthorized access']);
        }
    } else {
        echo json_encode(['error' => 'Authorization token not found']);
    }
} else {
    echo json_encode(['error' => 'Authorization header is missing']);
}
?>
