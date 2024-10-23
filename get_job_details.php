<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;  
include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $jobId = $_GET['job_id'] ?? ''; 

    if (empty($jobId)) {
        echo json_encode(["error" => "Job ID is required."]);
        http_response_code(400);
        exit();
    }

    // البحث عن تفاصيل الوظيفة باستخدام job_id
    $sql = "SELECT job_title, job_description, Company_name, Company_location, salary, created_at 
            FROM job_posts 
            WHERE job_id = ?"; // استخدام job_id في الاستعلام
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $jobDetails = $result->fetch_assoc();
        echo json_encode($jobDetails);
    } else {
        echo json_encode(["message" => "No job found with this ID."]);
    }

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>