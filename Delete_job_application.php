<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['application_id'])) {
    $stmt = $con->prepare("DELETE FROM job_application WHERE Application_id = ?");
    $stmt->bind_param("i", $data['application_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Job application deleted successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>