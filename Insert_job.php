<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['job_title']) &&
    isset($data['company_id']) &&
    isset($data['job_description']) &&
    isset($data['employment_type']) &&
    isset($data['job_location']) &&
    isset($data['salary_range']) &&
    isset($data['requirements']) &&
    isset($data['job_type'])
) {
    $stmt = $con->prepare("INSERT INTO jobs (Company_id, Job_title, Job_description, Employment_type, Job_location, Salary_range, Requirements, Job_type, Posted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssssss", $data['company_id'], $data['job_title'], $data['job_description'], $data['employment_type'], $data['job_location'], $data['salary_range'], $data['requirements'], $data['job_type']);
    
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Job created successfully."));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>