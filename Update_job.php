<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['job_id'])) {
    $job_id = $data['job_id'];
    
    $fields = array();
    $params = array();
    $types = '';

    if (isset($data['job_title'])) {
        $fields[] = "Job_title = ?";
        $params[] = $data['job_title'];
        $types .= 's';
    }
    if (isset($data['company_id'])) {
        $fields[] = "Company_id = ?";
        $params[] = $data['company_id'];
        $types .= 'i';
    }
    if (isset($data['job_description'])) {
        $fields[] = "Job_description = ?";
        $params[] = $data['job_description'];
        $types .= 's';
    }
    if (isset($data['employment_type'])) {
        $fields[] = "Employment_type = ?";
        $params[] = $data['employment_type'];
        $types .= 's';
    }
    if (isset($data['job_location'])) {
        $fields[] = "Job_location = ?";
        $params[] = $data['job_location'];
        $types .= 's';
    }
    if (isset($data['salary_range'])) {
        $fields[] = "Salary_range = ?";
        $params[] = $data['salary_range'];
        $types .= 's';
    }
    if (isset($data['requirements'])) {
        $fields[] = "Requirements = ?";
        $params[] = $data['requirements'];
        $types .= 's';
    }
    if (isset($data['job_type'])) {
        $fields[] = "Job_type = ?";
        $params[] = $data['job_type'];
        $types .= 's';
    }

    if (count($fields) > 0) {
        $query = "UPDATE jobs SET " . implode(", ", $fields) . " WHERE Job_id = ?";
        $params[] = $job_id;  
        $types .= 'i';  

        $stmt = $con->prepare($query);
        $stmt->bind_param($types, ...$params);  
        
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Job updated successfully."));
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