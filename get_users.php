<?php
require 'db_connection.php';

$sql_select = "SELECT User_id, User_name,  email, User_Education, User_Experience, User_Skills FROM users";
$result = mysqli_query($con, $sql_select);

$users = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
   }
    echo json_encode($users);
} else {
    echo json_encode(["status" => "error", "message" => "No users found"]);
}

mysqli_close($con);
?>
