<?php
require_once 'db_connection.php';
require_once 'vendor/autoload.php'; 


$headers = getallheaders();
if (isset($headers['User-ID'])) {
    $user_id = intval($headers['User-ID']); // تحويل الـ User-ID إلى عدد صحيح

    
    $query = "SELECT * FROM users WHERE User_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result()->fetch_assoc();

    
    $query = "SELECT * FROM experience WHERE User_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $experience_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    
    $query = "SELECT * FROM certificate WHERE User_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $certificate_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    
    $query = "SELECT * FROM skills WHERE User_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $skills_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    
    $response = [
        'user_info' => $user_result,
        'experience' => $experience_result,
        'certificate' => $certificate_result,
        'skills' => $skills_result,
    ];

    
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'User-ID header is missing']);
}
?>
