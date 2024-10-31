<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // إرسال رسالة النجاح بصيغة JSON فقط
    echo json_encode(["message" => "Logout successful"]);
    exit; // إنهاء التنفيذ هنا
} else {
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}
