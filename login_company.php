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

    if (!$email || !$password) {
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    $sql = "SELECT * FROM companies WHERE Email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email or password."]);
        exit();
    }

    $company = $result->fetch_assoc();

    if (!password_verify($password, $company['Password'])) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email or password."]);
        exit();
    }

    
    $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60), 
        'company_email' => $company['Email']
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    echo json_encode(["message" => "Login successful", "token" => $jwt]);
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
