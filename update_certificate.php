<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['certificate_id']) && isset($data['certificate_name']) && isset($data['institution']) && isset($data['issue_date']) && isset($data['description'])) {
    $stmt = $con->prepare("UPDATE certificate SET certificate_name = ?, institution = ?, issue_date = ?, description = ? WHERE certificate_id = ?");
    $stmt->bind_param("ssssi", $data['certificate_name'], $data['institution'], $data['issue_date'], $data['description'], $data['certificate_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Certificate updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
