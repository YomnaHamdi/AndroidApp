<?php

include_once 'db_connection.php'; 
require 'vendor/autoload.php'; 
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $con->prepare("SELECT User_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($User_id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 
            $expiration_time = time() + (60 * 60); 
            $token = array(
                "iat" => time(),
                "exp" => $expiration_time,
                "data" => array(
                    "User_id" => $User_id 
                )
            );

            
            $jwt = JWT::encode($token, $secret_key, 'HS256');

            
            try {
                $decodedToken = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                $userId = $decodedToken->data->User_id;

                
                echo json_encode(array(
                    "message" => "Login successful",
                    "jwt" => $jwt,
                    "userId" => $userId
                ));
            } catch (Exception $e) {
                echo json_encode(array("message" => "Error decoding JWT: " . $e->getMessage()));
            }
        } else {
            echo json_encode(array("message" => "Invalid password"));
        }
    } else {
        echo json_encode(array("message" => "User not found"));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);
?>
