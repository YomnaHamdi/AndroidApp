<?php

include_once 'db_connection.php';

if (isset($_GET['Job_id']);
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
