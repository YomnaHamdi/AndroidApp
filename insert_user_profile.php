<?php
require 'db_connection.php';

$User_id = mysqli_real_escape_string($con, $_POST['User_id']);
$Gender = mysqli_real_escape_string($con, $_POST['Gender']);
$Age = mysqli_real_escape_string($con, $_POST['Age']);
$Location = mysqli_real_escape_string($con, $_POST['Location']);
$Phone = mysqli_real_escape_string($con, $_POST['Phone']);
$Bio = mysqli_real_escape_string($con, $_POST['Bio']);
$User_image = mysqli_real_escape_string($con, $_POST['User_image']);


$sql_check_user = "SELECT id FROM Users WHERE id='$User_id'";
$result = mysqli_query($con, $sql_check_user);

if (mysqli_num_rows($result) > 0) {
    
    $sql_insert = "INSERT INTO user_profile (User_id, Gender, Age, Location, Phone, Bio, User_image) 
                   VALUES ('$User_id', '$Gender', '$Age', '$Location', '$Phone', '$Bio', '$User_image')";
    
    if (mysqli_query($con, $sql_insert)) {
        echo json_encode(["status" => "success", "message" => "User profile inserted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error inserting user profile: " . mysqli_error($con)]);
    }
} else {
    
    echo json_encode(["status" => "error", "message" => "Error: User ID does not exist in Users table"]);
}

mysqli_close($con);
?>