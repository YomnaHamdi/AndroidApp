<?php

include_once 'db_connection.php';

if (isset($_GET['job_id'])) {
    $stmt = $con->prepare("SELECT * FROM job WHERE job_id = ?");
    $stmt->bind_param("i", $_GET['job_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else {
    $result = $con->query("SELECT * FROM jobs");
    $jobs = array();
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    echo json_encode($jobs);
}

mysqli_close($con);

?>