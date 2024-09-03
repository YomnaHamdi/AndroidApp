<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['work_id']) && isset($data['title']) && isset($data['description']) && isset($data['file'])) {
    $stmt = $con->prepare("INSERT INTO uploadedwork (User_id, Work_id, Title, Description, File, Uploaded_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $data['user_id'], $data['work_id'], $data['title'], $data['description'], $data['file']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Work uploaded successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
    
    $stmt->close();
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>