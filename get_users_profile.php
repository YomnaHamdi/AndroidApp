<?php
require 'db_connection.php';

$User_id = isset($_GET['User_id']) ? mysqli_real_escape_string($con, $_GET['User_id']) : null;


if ($User_id) {
    
    $sql_select = "SELECT * FROM user_profile WHERE User_id = $User_id";
    $result = mysqli_query($con, $sql_select);

    if (mysqli_num_rows($result) > 0) {
        $profile = mysqli_fetch_assoc($result); 
        echo json_encode($profile); 
    } else {
        echo json_encode(["status" => "error", "message" => "User profile not found"]);
    }
} else {
    
    $sql_select = "SELECT * FROM user_profile";
    $result = mysqli_query($con, $sql_select);

    if (mysqli_num_rows($result) > 0) {
        $profiles = mysqli_fetch_all($result, MYSQLI_ASSOC); 
        echo json_encode($profiles); 
    } else {
        echo json_encode(["status" => "error", "message" => "No user profiles found"]);
    }
}

mysqli_close($con);
?>
