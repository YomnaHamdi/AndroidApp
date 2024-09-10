<?php
// قراءة متغيرات البيئة من Railway
$host = getenv('DB_HOST'); // مضيف قاعدة البيانات
$user = getenv('DB_USER'); // اسم المستخدم
$password = getenv('DB_PASS'); // كلمة المرور
$dbname = getenv('DB_NAME'); // اسم قاعدة البيانات
$port = getenv('DB_PORT'); // المنفذ (إذا لزم الأمر)

// إنشاء الاتصال بقاعدة البيانات
$con = new mysqli($host, $user, $password, $dbname, $port);

// echo ($con->connect_error);

// // التحقق من الاتصال
// if ($con->connect_error) {
//     die("فشل الاتصال بقاعدة البيانات: " . $con->connect_error);
// }
// echo "تم الاتصال بقاعدة البيانات بنجاح";
?>
