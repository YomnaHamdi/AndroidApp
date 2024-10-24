<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // الحصول على قيمة البحث 
    $searchTerm = $_GET['search'] ?? '';

    // استخدام SQL LIKE للبحث عن الوظائف مع JOIN مع جدول companies
    $sql = "SELECT j.job_title, j.job_description, c.Company_name, c.Company_location, j.salary, j.created_at, j.job_id 
            FROM job_posts j
            JOIN companies c ON j.Company_id = c.Company_id 
            WHERE j.job_title LIKE ? OR c.Company_name LIKE ?";

    $stmt = $con->prepare($sql);
    $searchWildcard = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $searchWildcard, $searchWildcard);
    $stmt->execute();
    $result = $stmt->get_result();

    $jobs = [];
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    echo json_encode($jobs);
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
