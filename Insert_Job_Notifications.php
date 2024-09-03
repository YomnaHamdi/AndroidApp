<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['job_id']) && isset($data['notification_content'])) {
    
    $stmt = $con->prepare("INSERT INTO job_notifications (User_id, Job_id, Notification_content, is_read, sent_at) VALUES (?, ?, ?, 0, NOW())");
    $stmt->bind_param("iis", $data['user_id'], $data['job_id'], $data['notification_content']);
    
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Notification added successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}


mysqli_close($con);

?>