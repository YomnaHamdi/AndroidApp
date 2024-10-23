<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['skill_id']) && isset($data['skill_name']) && isset($data['proficiency_level'])) {
    $stmt = $con->prepare("UPDATE skills SET skill_name = ?, proficiency_level = ? WHERE skill_id = ?");
    $stmt->bind_param("ssi", $data['skill_name'], $data['proficiency_level'], $data['skill_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Skill updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>