<?php
require 'db_connection.php';

$first_name = $_POST['User_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$User_Education = $_POST['User_Education'];
$User_Experience = $_POST['User_Experience'];
$User_Skillls = $_POST['User_Skills'];


$stmt = $con->prepare("INSERT INTO users (User_name, email, password, User_Education, User_Experience, User_Skills) VALUES ( ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $User_name, $email, $password, $User_Education, $User_Experience, $User_Skillls);


if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User inserted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error inserting user: " . $stmt->error]);
}

// إغلاق الاتصال
$stmt->close();
$con->close();
?>
