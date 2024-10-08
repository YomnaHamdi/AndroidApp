<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Notification_id']) && isset($data['Notification_content'])) {
    $stmt = $con->prepare("UPDATE job_notifications SET Notification_content = ?, Sent_at = NOW() WHERE Notification_id = ?");
    $stmt->bind_param("si", $data['Notification_content'], $data['Notification_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Notification updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
