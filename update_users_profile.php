<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
include 'auth_middleware.php';
include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
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
        
        $user_id = $decoded->user_id;

        // قراءة بيانات JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // التأكد من الحقول المرسلة فقط
        $fields = [];
        $params = [];
        
        if (isset($data['User_name'])) {
            $fields[] = "User_name = ?";
            $params[] = $data['User_name'];
        }
        if (isset($data['Age'])) {
            $fields[] = "Age = ?";
            $params[] = $data['Age'];
        }
        if (isset($data['Phone'])) {
            $fields[] = "Phone = ?";
            $params[] = $data['Phone'];
        }
        if (isset($data['Location'])) {
            $fields[] = "Location = ?";
            $params[] = $data['Location'];
        }
        if (isset($data['About'])) {
            $fields[] = "About = ?";
            $params[] = $data['About'];
        }

        if (empty($fields)) {
            echo json_encode(["error" => "No fields to update."]);
            exit();
        }

        // بناء استعلام التحديث ديناميكيًا
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE User_id = ?";
        $params[] = $user_id;
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($fields)) . "i", ...$params);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Profile updated successfully."]);
        } else {
            echo json_encode(["error" => "Error updating profile."]);
        }

    } catch (ExpiredException $e) {
        echo json_encode(["error" => "Token has expired."]);
        exit();
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token."]);
        exit();
    }

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
