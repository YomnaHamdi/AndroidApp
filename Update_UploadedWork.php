<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['Uploaded_id'])) {
    $fields = array();
    $params = array();
    $types = '';

    if (isset($data['Title'])) {
        $fields[] = "Title = ?";
        $params[] = $data['Title'];
        $types .= 's';
    }
    if (isset($data['Description'])) {
        $fields[] = "Description = ?";
        $params[] = $data['Description'];
        $types .= 's';
    }
    if (isset($data['Fle'])) {
        $fields[] = "File = ?";
        $params[] = $data['File'];
        $types .= 's';
    }

    if (count($fields) > 0) {
        $query = "UPDATE UploadedWork SET " . implode(", ", $fields) . ", Uploaded_at = NOW() WHERE Uploaded_id = ?";
        $params[] = $data['Uploaded_id'];
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
