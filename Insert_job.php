<?php

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['Job_id']) &&
    isset($data['Company_id']) &&
    isset($data['Job_title']) &&
    isset($data['Job_description']) &&
    isset($data['Employment_type']) &&
    isset($data['Job_location']) &&
    isset($data['Salary_range']) &&
    isset($data['Requirements']) &&
    isset($data['Job_type'])
) {
    // تعديل الاستعلام ليحتوي على 9 متغيرات فقط بدون Posted_at
    $stmt = $con->prepare("INSERT INTO jobs (Job_id, Company_id, Job_title, Job_description, Employment_type, Job_location, Salary_range, Requirements, Job_type, Posted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    // تعديل bind_param لتربط 8 متغيرات فقط
    $stmt->bind_param("isssssss", $data['Job_id'], $data['Company_id'], $data['Job_title'], $data['Job_description'], $data['Employment_type'], $data['Job_location'], $data['Salary_range'], $data['Requirements'], $data['Job_type']);
    
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
