<?php
require 'db_connection.php';
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$User_Education = $_POST['User_Education'];
$User_Experience = $_POST['User_Experience'];
$User_Skillls = $_POST['User_Skillls'];

$sql_insert = "INSERT INTO users (first_name, last_name, email, password, User_Education, User_Experience, User_Skillls) 
VALUES ('$first_name', '$last_name', '$email', '$password', '$User_Education', '$User_Experience', '$User_Skillls')";

if (mysqli_query($con, $sql_insert)) {
    echo json_encode(["status" => "success", "message" => "User inserted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error inserting user: " . mysqli_error($con)]);
}

mysqli_close($con);
?>
