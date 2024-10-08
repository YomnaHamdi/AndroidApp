<?php

include_once 'db_connection.php';

if (isset($_GET['Log_id'])) {
    $stmt = $con->prepare("SELECT * FROM logs WHERE Log_id = ?");
    $stmt->bind_param("i", $_GET['Log_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else if (isset($_GET['User_id'])) {
    $stmt = $con->prepare("SELECT * FROM logs WHERE User_id = ?");
    $stmt->bind_param("i", $_GET['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $logs = array();
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    echo json_encode($logs);
} else {
    echo json_encode(array("message" => "No Log id or User id provided"));
}

mysqli_close($con);

?>
