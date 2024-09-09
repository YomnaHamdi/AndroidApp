<?php
// قراءة متغيرات البيئة من Railway
$host = getenv('DB_HOST'); // مضيف قاعدة البيانات
$user = getenv('DB_USER'); // اسم المستخدم
$password = getenv('DB_PASS'); // كلمة المرور
$dbname = getenv('DB_NAME'); // اسم قاعدة البيانات
$port = getenv('DB_PORT'); // المنفذ (إذا لزم الأمر)

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $password, $dbname, $port);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
echo "تم الاتصال بقاعدة البيانات بنجاح";
?>
