<?php
<<<<<<< HEAD
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';
=======
include_once 'db_connection.php';
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

$data = json_decode(file_get_contents("php://input"), true);
>>>>>>> 44b059c8add5e4b222a71324c7aee6e81e0cc459

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

<<<<<<< HEAD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(["error" => "Token is required."]);
        exit();
    }

    try {
        $token = str_replace("Bearer ", "", $token);
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));  
        $email = $decoded->company_email; // استخدام البريد الإلكتروني من التوكن

        $data = json_decode(file_get_contents("php://input"));
        $companyName = $data->companyName ?? null;
        $companyLocation = $data->companyLocation ?? null;
        $industry = $data->industry ?? null;

        if (!$companyName || !$companyLocation || !$industry) {
            echo json_encode(["error" => "All fields are required."]);
            exit();
        }

        $sql_insert = "UPDATE companies SET Company_name = ?, Company_location = ?, Industry = ? WHERE Email = ?";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("ssss", $companyName, $companyLocation, $industry, $email);

        if ($stmt_insert->execute()) {
            echo json_encode(["message" => "Company details added successfully."]);
        } else {
            echo json_encode(["error" => "Error adding company details."]);
        }

    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }

} else {
    echo json_encode(["error" => "Method not allowed"]);
=======
if (
    isset($data['Company_name']) &&
    isset($data['Contact_person']) &&
    isset($data['location']) &&
    isset($data['industry'])
) {
    $Company_name = $data['Company_name'];
    $Contact_person = $data['Contact_person'];
    $location = $data['location'];
    $industry = $data['industry'];


    $stmt = $con->prepare("INSERT INTO companies (Company_name, Contact_person, location, industry) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $Company_name, $Contact_person, $location, $industry);

    if ($stmt->execute()) {
        $Company_id = $stmt->insert_id;

        
        $payload = [
            "data" => [
                "Company_id" => $Company_id
            ],
            "iat" => time(),
            "exp" => time() + (60 * 60)
        ];

        
        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        echo json_encode([
            "status" => "success",
            "message" => "Company added successfully",
            "Company_id" => $Company_id,
            "token" => $jwt 
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding company: " . $stmt->error]);
    }
    $stmt->close(); // أغلق الـ statement
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
>>>>>>> 44b059c8add5e4b222a71324c7aee6e81e0cc459
}
?>