<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Notification_id']) &&isset($data['User_id']) && isset($data['Job_id']) && isset($data['Notification_content'])) {
    
    $stmt = $con->prepare("INSERT INTO job_notifications (Notification_id,User_id, Job_id, Notification_content, Is_read, Sent_at) VALUES (?, ?, ?,?, 0, NOW())");
    $stmt->bind_param("iis",$data['Notification_id'], $data['User_id'], $data['Job_id'], $data['Notification_content']);
    
    
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
