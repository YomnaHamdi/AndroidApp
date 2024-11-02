<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';
include 'auth_middleware.php';
$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5";

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(["error" => "Token is required."]);
        exit();
    }

    try {
        $token = str_replace("Bearer ", "", $token);
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        $company_id = $decoded->Company_id;
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"));
    $job_id = $data->job_id ?? null;

    if (!$job_id) {
        echo json_encode(["error" => "Job ID is required."]);
        exit();
    }

    $sql = "DELETE FROM job_posts WHERE job_id = ? AND Company_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $job_id, $company_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Job post deleted successfully."]);
    } else {
        echo json_encode(["error" => "Error deleting job post."]);
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
