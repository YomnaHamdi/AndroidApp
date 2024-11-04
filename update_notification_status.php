<?php

// update_notification_status.php
include 'db_connection.php';

header('Content-Type: application/json'); 

$notification_id = $_POST['Notification_id'] ?? null;

if (!$notification_id || !is_numeric($notification_id)) {
    echo json_encode(["error" => "Invalid notification ID."]);
    exit();
}

$sql_update_status = "UPDATE job_notifications SET Is_read = 1 WHERE Notification_id = ?";
$stmt = $con->prepare($sql_update_status);

if ($stmt) {
    $stmt->bind_param("i", $notification_id); 
    if ($stmt->execute()) {
        echo json_encode(["message" => "Notification marked as read"]);
    } else {
        echo json_encode(["error" => "Error updating notification: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
}

$con->close();

?>
