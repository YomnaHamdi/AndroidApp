<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['User_id']) &&
    isset($data['Languages']) &&
    isset($data['Skills']) &&
    isset($data['WorkExperience']) &&
    isset($data['Education']) &&
    isset($data['Certifications'])
) {
    $stmt = $con->prepare("INSERT INTO curriculum_vitae (User_id, Created_at, Languages, Skills, WorkExperience, Education, Certifications) VALUES (?, NOW(), ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $data['User_id'], $data['Languages'], $data['Skills'], $data['WorkExperience'], $data['Education'], $data['Certifications']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "CV uploaded successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
