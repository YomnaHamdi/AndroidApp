<?php
require 'db_connection.php';


$Company_name = $_POST['Company_name'];
$Contact_person = $_POST['Contact_person'];
$Location = $_POST['Location'];
$Industry = $_POST['Industry'];

$sql_insert = "INSERT INTO companies (Company_name, Contact_person, Location, Industry) 
VALUES ('$Company_name', '$Contact_person', '$Location', '$Industry')";

if (mysqli_query($con, $sql_insert)) {
    echo json_encode(["status" => "success", "message" => "Company added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error adding company: " . mysqli_error($con)]);
}

mysqli_close($con);
?>