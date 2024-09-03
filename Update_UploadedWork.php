<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['work_id'])) {
    $fields = array();
    $params = array();
    $types = '';

    if (isset($data['title'])) {
        $fields[] = "Title = ?";
        $params[] = $data['title'];
        $types .= 's';
    }
    if (isset($data['description'])) {
        $fields[] = "Description = ?";
        $params[] = $data['description'];
        $types .= 's';
    }
    if (isset($data['file'])) {
        $fields[] = "File = ?";
        $params[] = $data['file'];
        $types .= 's';
    }

    if (count($fields) > 0) {
        $query = "UPDATE UploadedWork SET " . implode(", ", $fields) . ", Uploaded_at = NOW() WHERE Work_id = ?";
        $params[] = $data['work_id'];
        $types .= 'i';

        $stmt = $con->prepare($query);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Work updated successfully."));
        } else {
            echo json_encode(array("message" => "Error: " . $stmt->error));
        }
    } else {
        echo json_encode(array("message" => "No fields to update"));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>