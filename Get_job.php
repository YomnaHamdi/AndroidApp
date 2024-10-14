<?php

include_once 'db_connection.php';

if (isset($_GET['Job_id'])) {
    $Job_id = $_GET['Job_id'];
    $stmt = $con->prepare("
        SELECT jobs.*, companies.Company_name, companies.location, companies.industry
        FROM jobs
        JOIN companies ON jobs.Company_id = companies.Company_id
        WHERE jobs.Job_id = ?
    ");
    $stmt->bind_param("i", $Job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else {
    $result = $con->query("
        SELECT jobs.*, companies.Company_name, companies.location, companies.industry
        FROM jobs
        JOIN companies ON jobs.Company_id = companies.Company_id
    ");
    $jobs = array();
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    echo json_encode($jobs);
}

mysqli_close($con);

?>
