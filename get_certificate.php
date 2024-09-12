<?php

include_once 'db_connection.php';

if (isset($_GET['certificate_id'])) {
    $stmt = $con->prepare("SELECT * FROM certificate WHERE certificate_id = ?");
    $stmt->bind_param("i", $_GET['certificate_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(array("message" => "No Certificate id provided"));
}

mysqli_close($con);

?>
