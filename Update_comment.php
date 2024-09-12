<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['comment_id']) && isset($data['comment'])) {
    $stmt = $con->prepare("UPDATE comments SET comment = ?, created_at = NOW() WHERE comment_id = ?");
    $stmt->bind_param("si", $data['comment'], $data['comment_id']);
    
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
