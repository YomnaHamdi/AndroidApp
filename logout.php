<?php

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // في حالة وجود أي توكن يتم تجاهله، فقط نرسل رسالة النجاح.
    echo json_encode(["message" => "Logout successful"]);
    http_response_code(200);
} else {

    echo json_encode(["error" => "Method not allowed"]);
    http_response_code(405); // Method Not Allowed
}
