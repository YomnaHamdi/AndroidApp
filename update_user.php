<?php
require 'db_connection.php';

if (isset($_POST['id'], $_POST['User_name'],$_POST['password'], $_POST['email'], $_POST['User_Education'], $_POST['User_Experience'], $_POST['User_Skillls'])) {
    
  
    $id = mysqli_real_escape_string($con, $_POST['User_id']);
    $User_name = mysqli_real_escape_string($con, $_POST['User_name']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $User_Education = mysqli_real_escape_string($con, $_POST['User_Education']);
    $User_Experience = mysqli_real_escape_string($con, $_POST['User_Experience']);
    $User_Skillls = mysqli_real_escape_string($con, $_POST['User_Skills']);
    
    
    if (is_numeric($id)) {
        
        $sql_update = "UPDATE users SET User_id='$User_id',User_name='$User_name',  email='$email',password='$password',
        User_Education='$User_Education', User_Experience='$User_Experience', User_Skills='$User_Skills' WHERE User_id=$User_id";
        
        
        if (mysqli_query($con, $sql_update)) {
            echo json_encode(["status" => "success", "message" => "User updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating user: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
}


mysqli_close($con);
?>
