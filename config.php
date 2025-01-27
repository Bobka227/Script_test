<?php
// Настройки подключения к базе данных
$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$username = 'wk4kwaf4w8x12twh';
$password = 'ijw8uyd2lwkgf8on';

// Создаем подключение через MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
