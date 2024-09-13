<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['cv_id']) &&
    isset($data['User_id']) &&
    isset($data['Languages']) &&
    isset($data['Skills']) &&
    isset($data['WorkExperience']) &&
    isset($data['Education']) &&
    isset($data['Certifications'])
     
) {
    $stmt = $con->prepare("UPDATE  Scurriculum_vitae SET User_id =?,Languages = ?, Skills = ?, WorkExperience = ?, Education = ?, Certifications = ?, Updated_at = NOW() WHERE cv_id = ?");
    $stmt->bind_param("sssssi",$data['User_id'], $data['Languages'], $data['Skills'], $data['WorkExperience'], $data['Education'], $data['Certifications'], $data['cv_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "CV updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
