<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

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
        
        // سجل البيانات المفككة في ملف log.txt للتحقق
        file_put_contents('log.txt', print_r($decoded, true), FILE_APPEND);

        $user_id = $decoded->user_id;

        $User_name = $_POST['User_name'] ?? null;
        $Age = $_POST['Age'] ?? null;
        $Phone = $_POST['Phone'] ?? null;
        $Location = $_POST['Location'] ?? null;
        $About = $_POST['About'] ?? null;

        $sql = "UPDATE users SET User_name = ?, Age = ?, Phone = ?, Location = ?, About = ? WHERE User_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $User_name, $Age, $Phone, $Location, $About, $user_id);

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
?>
