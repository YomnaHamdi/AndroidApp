<?php
require 'connect.php';

// الحصول على البيانات من جسم الطلب (للطلبات من نوع DELETE)
parse_str(file_get_contents("php://input"), $data);

// تنظيف User_id وتأكد من أنه رقم صحيح
$User_id = mysqli_real_escape_string($con, $data['User_id']);

if (is_numeric($User_id)) {
    $sql_delete = "DELETE FROM user_profile WHERE User_id=$User_id";

    if (mysqli_query($con, $sql_delete)) {
        echo json_encode(["status" => "success", "message" => "User profile deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting user profile: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid User ID"]);
}

mysqli_close($con);
?>
