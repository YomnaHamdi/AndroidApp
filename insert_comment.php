<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Uploaded_id']) && isset($data['User_id']) && isset($data['Comment'])) {
    $stmt = $con->prepare("INSERT INTO comments (Uploaded_id, User_id, Comment, Uploaded_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $data['Uploaded_id'], $data['User_id'], $data['Comment']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Comment added successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>