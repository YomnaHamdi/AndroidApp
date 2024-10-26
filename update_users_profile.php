<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

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

        // قراءة الحقول المرسلة فقط
        $fields = [];
        $params = [];
        
        if (isset($_POST['User_name'])) {
            $fields[] = "User_name = ?";
            $params[] = $_POST['User_name'];
        }
        if (isset($_POST['Age'])) {
            $fields[] = "Age = ?";
            $params[] = $_POST['Age'];
        }
        if (isset($_POST['Phone'])) {
            $fields[] = "Phone = ?";
            $params[] = $_POST['Phone'];
        }
        if (isset($_POST['Location'])) {
            $fields[] = "Location = ?";
            $params[] = $_POST['Location'];
        }
        if (isset($_POST['About'])) {
            $fields[] = "About = ?";
            $params[] = $_POST['About'];
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
