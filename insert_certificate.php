<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name']) && isset($data['from']) && isset($data['date']) && isset($data['description'])) {
    
    $stmt = $con->prepare("INSERT INTO certificate (Name, `From`, `Date`, Description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['name'], $data['from'], $data['date'], $data['description']);
    
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