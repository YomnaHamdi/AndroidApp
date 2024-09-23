<?php

include_once 'db_connection.php';

if (isset($_GET['comment_id'])) {
    $comment_id = intval($_GET['comment_id']);
    
    if ($comment_id > 0) {
        $stmt = $con->prepare("DELETE FROM comments WHERE comment_id = ?");
        $stmt->bind_param("i", $comment_id);
        
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Comment deleted successfully."));
        } else {
            echo json_encode(array("message" => "Error: " . $stmt->error));
        }
    } else {
        echo json_encode(array("message" => "Invalid Comment ID"));
    }
} else {
    echo json_encode(array("message" => "Comment ID is required"));
}

mysqli_close($con);

?>
