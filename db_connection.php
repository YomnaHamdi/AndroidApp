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

$host = getenv('MYSQLHOST'); 
$user = getenv('MYSQLUSER'); 
$password = getenv('MYSQLPASSWORD'); 
$dbname = getenv('MYSQLDATABASE'); 
$port = getenv('MYSQLPORT'); 

// إنشاء الاتصال بقاعدة البيانات
$con = new mysqli($host, $user, $password, $dbname, $port);


if ($con->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $con->connect_error);
}
<<<<<<< HEAD
//المفتاح السرى ل jwt
define('JWT_SECRET_KEY', '9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5');
=======

if (!defined('JWT_SECRET_KEY')) {
    define('JWT_SECRET_KEY', '9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5'); 
}
>>>>>>> 44b059c8add5e4b222a71324c7aee6e81e0cc459
//echo "تم الاتصال بقاعدة البيانات بنجاح";
?>