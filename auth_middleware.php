<?php
// إعداد ترويسة JSON
header('Content-Type: application/json');


include 'db_connection.php'; 

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    
    // تحقق إذا كان التوكن موجودًا في القائمة السوداء
    $stmt = $con->prepare("SELECT COUNT(*) FROM token_blacklist WHERE token = ? AND expiry_date > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    if ($count > 0) {
        echo json_encode(["error" => "Token is invalid"]);
        exit;
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Authorization header not found"]);
    exit;
}
