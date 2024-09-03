<?php
require 'db_connection.php';


$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && !empty($data['id'])) {
    $id = mysqli_real_escape_string($con, $data['id']);
    
    if (is_numeric($id)) {
        $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting user: " . $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID is required"]);
}

mysqli_close($con);
?>