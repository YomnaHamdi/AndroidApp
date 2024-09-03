<?php

include_once 'db_connection.php';

if (isset($_GET['Comment_id'])) {
    $stmt = $con->prepare("SELECT * FROM comments WHERE Comment_id = ?");
    $stmt->bind_param("i", $_GET['Comment_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());


} else if (isset($_GET['Uploaded_id'])) {
    $stmt = $con->prepare("SELECT * FROM comments WHERE Uploaded_id = ?");
    $stmt->bind_param("i", $_GET['Uploaded_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = array();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode($comments);


} else {
    echo json_encode(array("message" => "No Comment_id or Uploaded_id provided"));
}

mysqli_close($con);

?>