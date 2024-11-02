<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php'; 
include 'auth_middleware.php';

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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

    $sql_company = "SELECT Company_name, Industry FROM companies WHERE Company_id = ?";
    $stmt_company = $con->prepare($sql_company);
    $stmt_company->bind_param("i", $company_id);
    $stmt_company->execute();
    $result_company = $stmt_company->get_result();

    if ($result_company->num_rows === 0) {
        echo json_encode(["error" => "Company not found."]);
        exit();
    }

    $company_data = $result_company->fetch_assoc();

    
    $sql_posts = "SELECT job_id, job_title, job_description, job_type, salary, created_at FROM job_posts WHERE Company_id = ?";
    $stmt_posts = $con->prepare($sql_posts);
    $stmt_posts->bind_param("i", $company_id);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();

    $posts = [];
    while ($post = $result_posts->fetch_assoc()) {
        $posts[] = $post; 
    }

    echo json_encode([
        "company_name" => $company_data['Company_name'],
        "industry" => $company_data['Industry'],
        "posts" => $posts
    ]);

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
