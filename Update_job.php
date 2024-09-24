<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['job_id'])) {
    $job_id = $data['job_id'];
    
    $fields = array();
    $params = array();
    $types = '';

    if (isset($data['Job_title'])) {
        $fields[] = "Job_title = ?";
        $params[] = $data['Job_title'];
        $types .= 's';
    }
    if (isset($data['Company_id'])) {
        $fields[] = "Company_id = ?";
        $params[] = $data['Company_id'];
        $types .= 'i';
    }
    if (isset($data['Job_description'])) {
        $fields[] = "Job_description = ?";
        $params[] = $data['Job_description'];
        $types .= 's';
    }
    if (isset($data['Employment_type'])) {
        $fields[] = "Employment_type = ?";
        $params[] = $data['Employment_type'];
        $types .= 's';
    }
    if (isset($data['Job_location'])) {
        $fields[] = "Job_location = ?";
        $params[] = $data['Job_location'];
        $types .= 's';
    }
    if (isset($data['Salary_range'])) {
        $fields[] = "Salary_range = ?";
        $params[] = $data['Salary_range'];
        $types .= 's';
    }
    if (isset($data['Requirements'])) {
        $fields[] = "Requirements = ?";
        $params[] = $data['Requirements'];
        $types .= 's';
    }
    if (isset($data['Job_type'])) {
        $fields[] = "Job_type = ?";
        $params[] = $data['Job_type'];
        $types .= 's';
    }
    
    if (isset($data['star'])) {
        $fields[] = "star = ?";
        $params[] = $data['star'];
        $types .= 'd'; //  (double)
    }
    if (isset($data['Date'])) {
        $fields[] = "Date = ?";
        $params[] = $data['Date'];
        $types .= 's';
    }
    if (isset($data['job_mode'])) {
        $fields[] = "job_mode = ?";
        $params[] = $data['job_mode'];
        $types .= 's';
    }
    if (isset($data['industry'])) {
        $fields[] = "industry = ?";
        $params[] = $data['industry'];
        $types .= 's';
    }
    if (isset($data['company_name'])) {
        $fields[] = "company_name = ?";
        $params[] = $data['company_name'];
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
