<?php

include_once 'db_connection.php';


if (!$con) {
    echo json_encode(array("message" => "Database connection failed: " . mysqli_connect_error()));
    exit();
}


$data = json_decode(file_get_contents("php://input"), true);


if (
    isset($data['Company_id']) &&
    isset($data['Job_title']) &&
    isset($data['Job_description']) &&
    isset($data['Employment_type']) &&
    isset($data['Job_location']) &&
    isset($data['Salary_range']) &&
    isset($data['Requirements']) &&
    isset($data['Job_type']) &&
    isset($data['job_mode'])
) {
    
    $stmt = $con->prepare("INSERT INTO jobs (Company_id, Job_title, Job_description, Employment_type, Job_location, Salary_range, Requirements, Job_type, Posted_at, job_mode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    
    if ($stmt) {
        
        $stmt->bind_param("issssssis", $data['Company_id'], $data['Job_title'], $data['Job_description'], $data['Employment_type'], $data['Job_location'], $data['Salary_range'], $data['Requirements'], $data['Job_type'], $data['job_mode']);
        
        
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Job created successfully."));
        } else {
            echo json_encode(array("message" => "Error: " . $stmt->error));
        }
    } else {
        echo json_encode(array("message" => "Failed to prepare the SQL statement."));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}


mysqli_close($con);

?>
