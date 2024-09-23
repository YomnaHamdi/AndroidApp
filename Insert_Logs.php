<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['User_id']) && isset($data['Action'])) {
    $stmt = $con->prepare("INSERT INTO logs (User_id, Timestamp, Action) VALUES (?, NOW(), ?)");
    $stmt->bind_param("is", $data['User_id'], $data['Action']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Log added successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
