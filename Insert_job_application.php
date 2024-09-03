<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['job_id']) && isset($data['status']) && isset($data['cv_id'])) {
    $stmt = $con->prepare("INSERT INTO job_application (User_id, Job_id, Status, Applied_at, CV_id) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->bind_param("iisi", $data['user_id'], $data['job_id'], $data['status'], $data['cv_id']);
    
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