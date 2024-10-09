<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['skill_id'])) {
    $stmt = $con->prepare("DELETE FROM skills WHERE skill_id = ?");
    $stmt->bind_param("i", $data['skill_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Skill deleted successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>