<?php

include_once 'db_connection.php';
require 'vendor/autoload.php'; 
use \Firebase\JWT\JWT;
require 'auth.php';

$data = json_decode(file_get_contents("php://input"), true);

//  (Authorization)
$jwt = isset($_SERVER['HTTP_AUTHORIZATION']) ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) : null;

if ($jwt) {
    try {
        $secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 
        $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($secret_key, 'HS256'));
        $User_id = $decoded->data->User_id; // استخدم الـ user_id من التوكن

        
        if (
            isset($data['User_name']) &&
            isset($data['Phone']) &&
            isset($data['Languages']) &&
            isset($data['Education'])
        ) {
            
            $stmt_check = $con->prepare("SELECT * FROM curriculum_vitae WHERE User_id = ?");
            $stmt_check->bind_param("s", $User_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                echo json_encode(array("message" => "CV already exists for this user."));
            } else {
                
                $stmt = $con->prepare("INSERT INTO curriculum_vitae (User_id, User_name, Phone, Created_at, Languages, Education) VALUES (?, ?, ?, NOW(), ?, ?)");
                $stmt->bind_param("sssss", $user_id, $data['User_name'], $data['Phone'], $data['Languages'], $data['Education']);
                
                if ($stmt->execute()) {
                    echo json_encode(array("message" => "CV uploaded successfully."));
                } else {
                    echo json_encode(array("message" => "Error: " . $stmt->error));
                }
            }
        } else {
            echo json_encode(array("message" => "Invalid input"));
        }
    } catch (Exception $e) {
        echo json_encode(array("message" => "Access denied.", "error" => $e->getMessage()));
    }
} else {
    echo json_encode(array("message" => "JWT not provided."));
}

mysqli_close($con);

?>
