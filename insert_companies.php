<?php
include_once 'db_connection.php';
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

$data = json_decode(file_get_contents("php://input"), true);


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
}

mysqli_close($con);
?>
