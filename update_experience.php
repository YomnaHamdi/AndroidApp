<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['experience_id']) &&
    isset($data['company']) &&
    isset($data['start_date']) &&
    isset($data['end_date']) &&
    isset($data['description'])
) {
    $stmt = $con->prepare("UPDATE Experience SET company = ?, start_date = ?, end_date = ?, description = ? WHERE experience_id = ?");
    $stmt->bind_param("ssssi", $data['company'], $data['start_date'], $data['end_date'], $data['description'], $data['experience_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Experience updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
