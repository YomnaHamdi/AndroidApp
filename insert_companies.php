<?php
require 'db_connection.php';


$Company_name = $_POST['Company_name'];
$Contact_person = $_POST['Contact_person'];
$location = $_POST['location'];
$industry = $_POST['industry'];


$sql_insert = "INSERT INTO companies (Company_name, Contact_person, location, industry) VALUES ('$Company_name', '$Contact_person', '$location', '$industry')";

if (mysqli_query($con, $sql_insert)) {
    
    $Company_id = mysqli_insert_id($con);
    echo json_encode(["status" => "success", "message" => "Company added successfully", "Company_id" => $Company_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Error adding company: " . mysqli_error($con)]);
}

mysqli_close($con);
?>
