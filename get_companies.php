<?php
require 'db_connection.php';


$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;


if ($company_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid Company ID"]);
    exit();
}

$company_id = mysqli_real_escape_string($con, $company_id);


$sql_select = "SELECT * FROM companies WHERE company_id = $company_id";
$result = mysqli_query($con, $sql_select);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Error executing query: " . mysqli_error($con)]);
    mysqli_close($con);
    exit();
}

if (mysqli_num_rows($result) > 0) {
    $company = mysqli_fetch_assoc($result);
    echo json_encode($company);
} else {
    echo json_encode(["status" => "error", "message" => "Company not found"]);
}

mysqli_close($con);
?>
