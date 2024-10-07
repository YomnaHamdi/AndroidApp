<?php

include_once 'db_connection.php'; 
require 'vendor/autoload.php'; 
use \Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['email']) &&
    isset($data['password']) &&
    isset($data['confirm_password'])
) {
    $email = mysqli_real_escape_string($con, $data['email']);
    $password = $data['password'];
    $confirmPassword = $data['confirm_password'];

    
    if ($password !== $confirmPassword) {
        echo json_encode(array("message" => "Passwords do not match"));
        exit();
    }

    
    $stmt = $con->prepare("SELECT User_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(array("message" => "User already exists"));
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $con->prepare("INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        $User_id = $stmt->insert_id; 
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

        
        $decodedToken = JWT::decode($jwt, $secret_key, array('HS256'));
        $userId = $decodedToken->data->User_id;

        
        $link = "https://androidapp-production.up.railway.app/token?key=$userId";

    
        echo json_encode(array(
            "message" => "User registered successfully",
            "jwt" => $jwt, 
            "link" => $link 
        ));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);
?>
