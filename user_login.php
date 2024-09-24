<?php

include_once 'db_connection.php'; 
require 'vendor/autoload.php'; // تحميل مكتبة JWT

use \Firebase\JWT\JWT;

$secret_key = "YOUR_SECRET_KEY"; // المفتاح السري لتشفير JWT

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $con->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // إذا كانت بيانات الدخول صحيحة، نولد JWT
            $issuer = "http://yourwebsite.com"; // مصدر التوكن
            $issued_at = time();
            $expiration_time = $issued_at + (60 * 60); // ساعة واحدة
            $token_data = array(
                "iss" => $issuer,
                "iat" => $issued_at,
                "exp" => $expiration_time,
                "data" => array(
                    "user_id" => $id
                )
            );

            // تشفير التوكن
            $jwt = JWT::encode($token_data, $secret_key, 'HS256');

            // إرسال التوكن مع الرد
            echo json_encode(array(
                "message" => "Login successful",
                "jwt" => $jwt
            ));
        } else {
            echo json_encode(array("message" => "Invalid password"));
        }
    } else {
        echo json_encode(array("message" => "User not found"));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
