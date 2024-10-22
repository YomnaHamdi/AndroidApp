<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;

include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $data = json_decode(file_get_contents("php://input"));

    $User_name = $data->User_name ?? null;
    $Gender = $data->Gender ?? null;
    $Age = $data->Age ?? null;
    $Phone = $data->Phone ?? null;
    $Location = $data->Location ?? null;
    $About = $data->About ?? null;
    $password = $data->password ?? null;

    if (!$User_name || !$Gender || !$Age || !$Phone || !$Location || !$About || !$password) {
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql_check = "SELECT * FROM users WHERE User_name = ?";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("s", $User_name);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Username already exists"]);
        exit();
    }

    $sql = "INSERT INTO users (User_name, password, Gender, Age, Phone, Location, About, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssss", $User_name, $hashedPassword, $Gender, $Age, $Phone, $Location, $About);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60), 
            'user_id' => $user_id,
            'username' => $User_name
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        echo json_encode(["message" => "Registration successful", "token" => $jwt]);
    } else {
        echo json_encode(["error" => "Error inserting data"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
