<?php

header('Content-Type: application/json');

include 'db_connection.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        
        // إدراج التوكن في قائمة Blacklist مع تاريخ انتهاء صلاحيته
        $expiry_date = date('Y-m-d H:i:s', strtotime('+1 hour')); 
        $stmt = $con->prepare("INSERT INTO token_blacklist (token, expiry_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $token, $expiry_date);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Logout successful"]);
        } else {
            echo json_encode(["error" => "Could not log out"]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["error" => "Authorization header not found"]);
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}

$con->close();
exit;
