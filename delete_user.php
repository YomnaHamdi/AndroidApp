<?php
require 'db_connection.php';


$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['User_id']) && !empty($data['User_id'])) {
    $User_id = mysqli_real_escape_string($con, $data['User_id']);
    
    if (is_numeric($User_id)) {
        $stmt = $con->prepare("DELETE FROM users WHERE User_id = ?");
        $stmt->bind_param("i", $User_id);
        
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
