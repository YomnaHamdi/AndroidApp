<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $email = $data->email ?? null;
    $password = $data->password ?? null;
    $confirmPassword = $data->confirm_password ?? null;

    if (!$email || !$password || !$confirmPassword) {
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    if ($password !== $confirmPassword) {
        echo json_encode(["error" => "Passwords do not match."]);
        exit();
    }

    
    $sql_check_email = "SELECT * FROM companies WHERE Email = ?";
    $stmt_check_email = $con->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        echo json_encode(["error" => "Email already exists."]);
        exit();
    }

  
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $sql_insert = "INSERT INTO companies (Email, Password) VALUES (?, ?)";
    $stmt_insert = $con->prepare($sql_insert);
    $stmt_insert->bind_param("ss", $email, $hashedPassword);

    if ($stmt_insert->execute()) {
        
        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60), 
            'company_email' => $email
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        echo json_encode(["message" => "Sign up successful", "token" => $jwt]);
    } else {
        echo json_encode(["error" => "Error during registration."]);
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
