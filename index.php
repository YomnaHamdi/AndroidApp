<?php
echo "This is the index.php file running!";


// الحصول على المنفذ من المتغير البيئي أو استخدام 9000 كمنفذ افتراضي
$port = getenv('PORT') ?: 9000;
$host = '0.0.0.0';

// عرض رسالة في حالة بدء السيرفر
echo "Server running on http://$host:$port\n";

// تشغيل السيرفر باستخدام PHP's built-in server
exec("php -S $host:$port -t public/");

