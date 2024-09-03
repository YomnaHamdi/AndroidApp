<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['log_id']) && isset($data['action'])) {
    $stmt = $con->prepare("UPDATE logs SET Action = ?, Timestamp = NOW() WHERE Log_id = ?");
    $stmt->bind_param("si", $data['action'], $data['log_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Log updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>