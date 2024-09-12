<?php
require 'db_connection.php';


if (isset($_POST['company_id']) && isset($_POST['Company_name']) && isset($_POST['Contact_person']) &&
    isset($_POST['location']) && isset($_POST['industry'])) {

  
    $company_id = intval($_POST['company_id']);
    $Company_name = mysqli_real_escape_string($con, $_POST['Company_name']);
    $Contact_person = mysqli_real_escape_string($con, $_POST['Contact_person']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $industry = mysqli_real_escape_string($con, $_POST['industry']);

  
    if ($company_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid Company ID"]);
        exit();
    }

    
    $sql_update = "UPDATE Companies SET Company_name='$Company_name', Contact_person='$Contact_person', location='$location', industry='$industry' WHERE company_id=$company_id";

    if (mysqli_query($con, $sql_update)) {
        echo json_encode(["status" => "success", "message" => "Company updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating company: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
}

mysqli_close($con);
?>
