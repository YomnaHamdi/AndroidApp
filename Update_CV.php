<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['cv_id']) &&
    isset($data['languages']) &&
    isset($data['skills']) &&
    isset($data['work_experience']) &&
    isset($data['education']) &&
    isset($data['certifications'])
) {
    $stmt = $con->prepare("UPDATE cv SET Languages = ?, Skills = ?, WorkExperience = ?, Education = ?, Certifications = ?, Updated_at = NOW() WHERE CV_id = ?");
    $stmt->bind_param("sssssi", $data['languages'], $data['skills'], $data['work_experience'], $data['education'], $data['certifications'], $data['cv_id']);
    
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