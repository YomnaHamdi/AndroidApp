<?php

include_once 'db_connection.php';

if (isset($_GET['work_id'])) {
    $stmt = $con->prepare("SELECT * FROM uploadedwork WHERE Work_id = ?");
    $stmt->bind_param("i", $_GET['work_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else if (isset($_GET['user_id'])) {
    $stmt = $con->prepare("SELECT * FROM uploadedwork WHERE User_id = ?");
    $stmt->bind_param("i", $_GET['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $works = array();
    while ($row = $result->fetch_assoc()) {
        $works[] = $row;
    }
    echo json_encode($works);
} else {
    echo json_encode(array("message" => "No Work id or User id provided"));
}

mysqli_close($con);

?>