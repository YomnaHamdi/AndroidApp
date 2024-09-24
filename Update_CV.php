<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['cv_id'])) {
    $updates = [];
    $params = [];
    
    
    if (isset($data['User_id'])) {
        $updates[] = "User_id = ?";
        $params[] = $data['User_id'];
    }
    if (isset($data['Languages'])) {
        $updates[] = "Languages = ?";
        $params[] = $data['Languages'];
    }
    if (isset($data['Education'])) {
        $updates[] = "Education = ?";
        $params[] = $data['Education'];
    }


    if (count($updates) > 0) {
        
        $sql = "UPDATE Scurriculum_vitae SET " . implode(", ", $updates) . ", Updated_at = NOW() WHERE cv_id = ?";
        $params[] = $data['cv_id'];

        $stmt = $con->prepare($sql);
        
        
        $types = str_repeat("s", count($params) - 1) . "i"; 
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo json_encode(array("message" => "CV updated successfully."));
        } else {
            echo json_encode(array("message" => "Error: " . $stmt->error));
        }
    } else {
        echo json_encode(array("message" => "No fields to update."));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
