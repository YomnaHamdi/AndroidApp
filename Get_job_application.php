<?php

include_once 'db_connection.php';

if (isset($_GET['Application_id'])) {
    $stmt = $con->prepare("SELECT * FROM job_application WHERE Application_id = ?");
    $stmt->bind_param("i", $_GET['Application_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else {
    $result = $con->query("SELECT * FROM job_application");
    $applications = array();
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
    echo json_encode($applications);
}

mysqli_close($con);

?>
