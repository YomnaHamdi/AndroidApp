<?php

include_once 'db_connection.php';

if (isset($_GET['Notification_id'])) {
    $stmt = $con->prepare("SELECT * FROM job_notifications WHERE Notification_id = ?");
    $stmt->bind_param("i", $_GET['Notification_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else if (isset($_GET['User_id'])) {
    $stmt = $con->prepare("SELECT * FROM job_notifications WHERE User_id = ?");
    $stmt->bind_param("i", $_GET['User_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = array();
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    echo json_encode($notifications);
} else {
    echo json_encode(array("message" => "No Notification id or User id provided"));
}

mysqli_close($con);

?>
