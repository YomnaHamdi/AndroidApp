<?php

require 'db_connection.php'; 
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


$jwt = isset($_SERVER['HTTP_AUTHORIZATION']) ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) : null;

if ($jwt) {
    try {
        
        $secret_key = JWT_SECRET_KEY; 
        
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        
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
