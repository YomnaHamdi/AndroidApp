<?php
require 'db_connection.php';


$Company_id = isset($_GET['Company_id']) ? intval($_GET['Company_id']) : 0;


if ($Company_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid Company ID"]);
    exit();
}

$Company_id = mysqli_real_escape_string($con, $Company_id);


$sql_select = "SELECT * FROM companies WHERE Company_id = $Company_id";
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