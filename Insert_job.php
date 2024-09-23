<?php

include_once 'db_connection.php';

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
    isset($data['star']) && 
    isset($data['Date']) &&
    isset($data['job_mode']) &&
    isset($data['company_name']) 
) {
    
    $stmt = $con->prepare("INSERT INTO jobs ( Company_id, Job_title, Job_description, Employment_type, Job_location, Salary_range, Requirements, Job_type, Posted_at, star,Date,job_mode, company_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?,?,?, ?)");
    
    
    $stmt->bind_param("isssssssisss",  $data['Company_id'], $data['Job_title'], $data['Job_description'], $data['Employment_type'], $data['Job_location'], $data['Salary_range'], $data['Requirements'], $data['Job_type'], $data['star'],$data['Date'],$data['job_mode'], $data['company_name']);
    
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
