<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Comment_id']) && isset($data['Comment'])) {
    $stmt = $con->prepare("UPDATE comments SET Comment = ?, Uploaded_at = NOW() WHERE Comment_id = ?");
    $stmt->bind_param("si", $data['Comment'], $data['Comment_id']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Comment updated successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>