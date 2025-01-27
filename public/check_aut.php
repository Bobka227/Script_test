<?php
session_start();
header('Content-Type: application/json');
$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$username = 'wk4kwaf4w8x12twh';
$password = 'ijw8uyd2lwkgf8on';

// Проверяем, зарегистрирован ли пользователь
if (isset($_SESSION['username'])) {
    echo json_encode(['authenticated' => true]);
} else {
    echo json_encode(['authenticated' => false]);
}
?>