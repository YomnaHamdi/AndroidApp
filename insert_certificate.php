<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);


if (isset($data['User_id']) && isset($data['certificate_name']) && isset($data['institution']) && isset($data['issue_date']) && isset($data['description'])) {
    
    
    $stmt = $con->prepare("INSERT INTO certificate (User_id, certificate_name, institution, issue_date, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $data['User_id'], $data['certificate_name'], $data['institution'], $data['issue_date'], $data['description']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Certificate added successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
