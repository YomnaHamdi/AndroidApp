<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;  
include 'db_connection.php'; 
include 'auth_middleware.php';

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(["error" => "Token is required."]);
        http_response_code(401);
        exit();
    }

    try {
        $token = str_replace("Bearer ", "", $token);
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        $company_id = $decoded->user_id; // ID الشركة من التوكن

        // الحصول على الـ user_id من الـ query parameter
        $user_id = $_GET['user_id'] ?? null;

        if (!$user_id) {
            echo json_encode(["error" => "User ID is required."]);
            exit();
        }

        
        $sql = "SELECT * FROM curriculum_vitae WHERE user_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cv = $result->fetch_assoc();

           
            $sql_user = "SELECT User_name, Phone FROM users WHERE User_id = ?";
            $stmt_user = $con->prepare($sql_user);
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();

            if ($result_user->num_rows > 0) {
                $user_data = $result_user->fetch_assoc();
                $cv['user_name'] = $user_data['User_name'];
                $cv['phone'] = $user_data['Phone'];
            }

            echo json_encode($cv); 
        } else {
            echo json_encode(["message" => "No CV found for this user."]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
