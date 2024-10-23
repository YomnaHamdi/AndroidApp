<?php

include_once 'db_connection.php';

if (isset($_GET['User_id'])) {
    $user_id = $_GET['User_id'];

    $stmt = $con->prepare("SELECT * FROM skills WHERE User_id = ?");
    $stmt->bind_param("i", $User_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $skills = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode($skills);
} else {
    echo json_encode(array("message" => "user_id is required"));
}

mysqli_close($con);

?>