<?php
// إعدادات CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// التعامل مع طلبات OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0);
}

$host = getenv('DB_HOST'); 
$user = getenv('DB_USER'); 
$password = getenv('DB_PASS'); 
$dbname = getenv('DB_NAME'); 
$port = getenv('DB_PORT'); 

// إنشاء الاتصال بقاعدة البيانات
$con = new mysqli($host, $user, $password, $dbname, $port);


if ($con->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $con->connect_error);
}
//echo "تم الاتصال بقاعدة البيانات بنجاح";
?>