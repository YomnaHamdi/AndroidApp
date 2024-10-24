<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';

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

        // استرجاع الإشعارات بناءً على الوظائف والأشخاص الذين تقدموا
        $sql = "SELECT j.job_title, u.id as User_id, u.User_name, n.Sent_at 
                FROM job_notifications n
                JOIN users u ON n.User_id = u.id
                JOIN job_posts j ON n.job_id = j.id
                WHERE j.Company_id = ?
                ORDER BY j.job_title, n.Sent_at DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $job_title = $row['job_title'];
            if (!isset($notifications[$job_title])) {
                $notifications[$job_title] = [];
            }

            $notifications[$job_title][] = [
                'user_id' => $row['User_id'],
                'username' => $row['User_name'],
                'Sent_at' => $row['Sent_at']
            ];
        }

        // إرجاع الإشعارات حسب الوظائف والمتقدمين
        echo json_encode(["notifications" => $notifications]);

    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
