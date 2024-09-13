<?php

include_once 'db_connection.php';

if (isset($_GET['cv_id'])) {
    $stmt = $con->prepare("SELECT * FROM curriculum_vitae WHERE cV_id = ?");
    $stmt->bind_param("i", $_GET['cv_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else if (isset($_GET['User_id'])) {
    $stmt = $con->prepare("SELECT * FROM curriculum_vitae WHERE User_id = ?");
    $stmt->bind_param("i", $_GET['User_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $cvs = array();
    while ($row = $result->fetch_assoc()) {
        $cvs[] = $row;
    }
    echo json_encode($cvs);
} else {
    echo json_encode(array("message" => "No CV id or User id provided"));
}

mysqli_close($con);

?>
