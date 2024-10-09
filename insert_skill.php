<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['User_id']) && isset($data['skill_name']) && isset($data['proficiency_level'])) {
    $stmt = $con->prepare("INSERT INTO skills (User_id, skill_name, proficiency_level) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $data['User_id'], $data['skill_name'], $data['proficiency_level']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Skill added successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
