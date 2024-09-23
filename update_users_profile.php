<?php
require 'db_connection.php';


$User_id = mysqli_real_escape_string($con, $_POST['User_id']);
$Gender = mysqli_real_escape_string($con, $_POST['Gender']);
$Age = mysqli_real_escape_string($con, $_POST['Age']);
$Location = mysqli_real_escape_string($con, $_POST['Location']);
$Phone = mysqli_real_escape_string($con, $_POST['Phone']);
$Bio = mysqli_real_escape_string($con, $_POST['Bio']);
$User_image = mysqli_real_escape_string($con, $_POST['User_image']);
$user_name = mysqli_real_escape_string($con, $_POST['user_name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$job_name = mysqli_real_escape_string($con, $_POST['job_name']);


if (is_numeric($User_id)) {
    
    $sql_update = "UPDATE User_Profile SET 
                   Gender='$Gender', 
                   Age='$Age', 
                   Location='$Location', 
                   Phone='$Phone', 
                   Bio='$Bio', 
                   User_image='$User_image',
                   user_name='$user_name',
                   email='$email',
                   job_name='$job_name'
                   WHERE User_id=$User_id";

    if (mysqli_query($con, $sql_update)) {
        echo json_encode(["status" => "success", "message" => "User profile updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating user profile: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid User ID"]);
}

mysqli_close($con);
?>
