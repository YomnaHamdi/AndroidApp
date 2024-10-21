<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include 'db_connection.php'; 


$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $data = json_decode(file_get_contents("php://input"));

    $username = $data->username ?? null;
    $gender = $data->gender ?? null;
    $location = $data->location ?? null;
    $phone = $data->phone ?? null;
    $about = $data->about ?? null;
    $job_name = $data->job_name ?? null;
    $password = $data->password ?? null;

    
    if (!$username || !$gender || !$location || !$phone || !$about || !$job_name || !$password) {
        echo json_encode(["error" => "All fields are required."]);
        http_response_code(400);
        exit();
    }

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $sql_check = "SELECT * FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode(["error" => "Username already exists"]);
        http_response_code(409);
        exit();
    }

    
    $sql = "INSERT INTO users (username, password, gender, location, phone, about, job_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $hashedPassword, $gender, $location, $phone, $about, $job_name);

    if ($stmt->execute()) {
        
        $user_id = $stmt->insert_id; 

    
        $payload = [
            'iat' => time(), 
            'exp' => time() + (60 * 60), 
            'user_id' => $user_id,
            'username' => $username
        ];

        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        
        echo json_encode(["message" => "Registration successful", "token" => $jwt]);
        http_response_code(201); 
    } else {
        echo json_encode(["error" => "Error inserting data"]);
        http_response_code(500);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Method not allowed"]);
    http_response_code(405); 
}
?>
