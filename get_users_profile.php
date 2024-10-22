<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 


$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

if ($token) {
    try {
       
        $decoded = JWT::decode($token, $secretKey, ['HS256']);
        $user_id = $decoded->user_id; 

        $sql = "SELECT User_name, Gender, Age, Location, Phone, About FROM users WHERE User_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
           
            echo json_encode($user_data);
        } else {
            echo json_encode(["error" => "No User Data Found."]);
        }

    } catch (ExpiredException $e) {
        echo json_encode(["error" => "Token Has Expired."]);
    } catch (Exception $e) {
        echo json_encode(["error" => "An Error occured while decoding the token."]);
    }
} else {
    echo json_encode(["error" => "Token is missing."]);
}
?>
