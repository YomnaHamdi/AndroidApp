<?php
require 'db_connection.php';


parse_str(file_get_contents("php://input"), $data);


if (isset($data['Company_id'])) {
    
    $Company_id = intval($data['Company_id']);

    
    if ($Company_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid Company ID"]);
        exit();
    }

    
    $stmt = $con->prepare("DELETE FROM companies WHERE Company_id = ?");
    $stmt->bind_param("i", $Company_id);

    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Company deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting company: " . $stmt->error]);
    }

    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing Company ID"]);
}


mysqli_close($con);
?>