<?php

require 'db_connection.php'; 
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// احصل على التوكن من الهيدر (Authorization)
$jwt = isset($_SERVER['HTTP_AUTHORIZATION']) ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) : null;

if ($jwt) {
    try {
        
        $secret_key = JWT_SECRET_KEY; 
        // فك تشفير التوكن
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        // احصل على user_id من التوكن
        $user_id = $decoded->data->user_id;

        
    } catch (Exception $e) {
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
        exit();
    }
} else {
    echo json_encode(array(
        "message" => "JWT not provided."
    ));
    exit();
}
?>