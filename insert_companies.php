<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    $email = $data->email ?? null; 
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

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
