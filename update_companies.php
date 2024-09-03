<?php
require 'db_connection.php';


if (isset($_POST['Company_id']) && isset($_POST['Company_name']) && isset($_POST['Contact_person']) &&
    isset($_POST['Location']) && isset($_POST['Industry'])) {

  
    $Company_id = intval($_POST['Company_id']);
    $Company_name = mysqli_real_escape_string($con, $_POST['Company_name']);
    $Contact_person = mysqli_real_escape_string($con, $_POST['Contact_person']);
    $Location = mysqli_real_escape_string($con, $_POST['Location']);
    $Industry = mysqli_real_escape_string($con, $_POST['Industry']);

  
    if ($Company_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid Company ID"]);
        exit();
    }

    
    $sql_update = "UPDATE Companies SET Company_name='$Company_name', Contact_person='$Contact_person', Location='$Location', Industry='$Industry' WHERE Company_id=$Company_id";

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