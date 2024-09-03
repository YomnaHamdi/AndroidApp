<?php

include_once 'db_connection.php';

if (isset($_GET['experience_id'])) {
    $stmt = $con->prepare("SELECT * FROM experience WHERE Experience_id = ?");
    $stmt->bind_param("i", $_GET['experience_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else if (isset($_GET['company'])) {
    $stmt = $con->prepare("SELECT * FROM experience WHERE Company = ?");
    $stmt->bind_param("s", $_GET['company']);
    $stmt->execute();
    $result = $stmt->get_result();
    $experiences = array();
    while ($row = $result->fetch_assoc()) {
        $experiences[] = $row;
    }
    echo json_encode($experiences);
} else {
    echo json_encode(array("message" => "No Experience id or Company provided"));
}

mysqli_close($con);

?>