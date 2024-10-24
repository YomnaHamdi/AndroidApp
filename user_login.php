<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $username = $data->username ?? null;
    $password = $data->password ?? null;

    if (!$username || !$password) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    $sql = "SELECT * FROM users WHERE User_name = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid username or password."]);
        exit();
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid username or password."]);
        exit();
    }

    $secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5";
    $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60), // 1 hour
        'user_id' => $user['User_id'],
        'username' => $user['User_name']
    ];

    $jwt = JWT::encode($payload, $secretKey,'HS256');

    echo json_encode(["message" => "Login successful", "token" => $jwt]);
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
