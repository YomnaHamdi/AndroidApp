<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    
    isset($data['company']) &&
    isset($data['start_date']) &&
    isset($data['end_date']) &&
    isset($data['description'])
) {
    $stmt = $con->prepare("INSERT INTO experience (company, start_date, end_date, description) VALUES (?, ?, ?, ?,?)");
    $stmt->bind_param("ssss", $data['company'], $data['start_date'], $data['end_date'], $data['description']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Experience added successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
