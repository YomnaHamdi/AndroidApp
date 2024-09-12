<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['User_id']) && isset($data['Uploaded_id']) && isset($data['Title']) && isset($data['Description']) && isset($data['File'])) {
    $stmt = $con->prepare("INSERT INTO UploadedWork (User_id, Uploaded_id, Title, Description, File, Uploaded_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $data['User_id'], $data['Uploaded_id'], $data['Title'], $data['Description'], $data['File']);
    
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
