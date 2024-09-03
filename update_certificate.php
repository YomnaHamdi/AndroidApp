<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['certificate_id']) && isset($data['name']) && isset($data['from']) && isset($data['date']) && isset($data['description'])) {
    $stmt = $con->prepare("UPDATE certificate SET Name = ?, `From` = ?, `Date` = ?, Description = ? WHERE certificate_id = ?");
    $stmt->bind_param("ssssi", $data['name'], $data['from'], $data['date'], $data['description'], $data['certificate_id']);
    
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