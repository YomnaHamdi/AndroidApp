<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Log_id'])) {
    $stmt = $con->prepare("DELETE FROM logs WHERE Log_id = ?");
    $stmt->bind_param("i", $data['Log_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Log deleted successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
