<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Application_id']) && isset($data['User_id']) && isset($data['job_id']) && isset($data['Status']) && isset($data['cv_id'])) {
    $stmt = $con->prepare("INSERT INTO job_application (Application_id, User_id, job_id, Status, applied_at, cv_id) VALUES (?, ?, ?,?, NOW(), ?)");
    $stmt->bind_param("iisi", $data['Application_id'],$data['User_id'], $data['job_id'], $data['Status'], $data['cv_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Job application created successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
