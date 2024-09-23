<?php
require 'db_connection.php';

if (isset($_POST['company_id'])) {

    $company_id = intval($_POST['company_id']);

    if ($company_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid Company ID"]);
        exit();
    }

    // بناء جملة التحديث ديناميكياً
    $updates = [];
    
    if (isset($_POST['Company_name'])) {
        $Company_name = mysqli_real_escape_string($con, $_POST['Company_name']);
        $updates[] = "Company_name='$Company_name'";
    }
    
    if (isset($_POST['Contact_person'])) {
        $Contact_person = mysqli_real_escape_string($con, $_POST['Contact_person']);
        $updates[] = "Contact_person='$Contact_person'";
    }
    
    if (isset($_POST['location'])) {
        $location = mysqli_real_escape_string($con, $_POST['location']);
        $updates[] = "location='$location'";
    }
    
    if (isset($_POST['industry'])) {
        $industry = mysqli_real_escape_string($con, $_POST['industry']);
        $updates[] = "industry='$industry'";
    }

    // التحقق مما إذا كانت هناك بيانات لتحديثها
    if (count($updates) > 0) {
        $sql_update = "UPDATE companies SET " . implode(', ', $updates) . " WHERE company_id=$company_id";

        if (mysqli_query($con, $sql_update)) {
            echo json_encode(["status" => "success", "message" => "Company updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating company: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No data provided for update"]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Missing required data: company_id"]);
}

mysqli_close($con);
?>
