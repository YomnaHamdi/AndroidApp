<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['cv_id']) &&
    isset($data['User_id']) &&
    isset($data['Languages']) &&
    isset($data['Education']) 

) {
    $stmt = $con->prepare("UPDATE  Scurriculum_vitae SET User_id =?,Languages = ?,  Education = ?, Updated_at = NOW() WHERE cv_id = ?");
    $stmt->bind_param("sssssi",$data['User_id'], $data['Languages'],  $data['Education'], $data['cv_id']);
    
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
