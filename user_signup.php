<?php

include_once 'db_connection.php'; 

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['email']) &&
    isset($data['password']) &&
    isset($data['confirm_password'])
) {
    $email = $data['email'];
    $password = $data['password'];
    $confirmPassword = $data['confirm_password'];

    // التحقق من مطابقة كلمة المرور
    if ($password !== $confirmPassword) {
        echo json_encode(array("message" => "Passwords do not match"));
        exit();
    }

    // التحقق من وجود المستخدم مسبقاً
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(array("message" => "User already exists"));
        exit();
    }

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $con->prepare("INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(array("message" => "User registered successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $stmt->error));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
