<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
include 'db_connection.php';
include 'auth_middleware.php';

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5"; 

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
        $userId = $decoded->user_id; 

        $data = json_decode(file_get_contents("php://input"));
        $jobId = $data->job_id ?? null;

        if (!$jobId) {
            echo json_encode(["error" => "Job ID is required."]);
            exit();
        }

        
        $sql_insert = "INSERT INTO job_application (User_id, job_id) VALUES (?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $userId, $jobId);

        if ($stmt_insert->execute()) {
            
            $applicationId = $stmt_insert->insert_id;

            //  Fetch job title for notification content
            $sql_job = "SELECT job_title FROM job_posts WHERE job_id = ?";
            $stmt_job = $con->prepare($sql_job);
            $stmt_job->bind_param("i", $jobId);
            $stmt_job->execute();
            $result_job = $stmt_job->get_result();
            $jobTitle = $result_job->fetch_assoc()['job_title'];

            //  Insert notification
            $notificationContent = "Apply a user for a job: " . $jobTitle;
            $isRead = 0;
            $sentAt = date('Y-m-d H:i:s');

            $sql_notification = "INSERT INTO job_notifications (Application_id, Notification_content, Is_read, Sent_at, job_id, User_id) 
                                 VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_notification = $con->prepare($sql_notification);
            $stmt_notification->bind_param("isiiii", $applicationId, $notificationContent, $isRead, $sentAt, $jobId, $userId);

            if ($stmt_notification->execute()) {
                echo json_encode(["message" => "Application submitted and notification created successfully."]);
            } else {
                echo json_encode(["error" => "Error creating notification."]);
            }
        } else {
            echo json_encode(["error" => "Error submitting application."]);
        }

    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
