<?php
header('Content-Type: application/json');
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // إرسال رسالة النجاح بصيغة JSON فقط
    echo json_encode(["message" => "Logout successful"]);
} else {
    echo json_encode(["error" => "Method not allowed"]);
}

ob_end_clean();
