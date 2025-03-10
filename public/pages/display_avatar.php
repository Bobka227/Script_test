<?php
session_start();

if (!isset($_SESSION['login'])) {
    exit("Unauthorized access");
}

$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$db_username = 'wk4kwaf4w8x12twh';
$db_password = 'ijw8uyd2lwkgf8on';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Database connection error");
}

// Получаем логин пользователя из сессии
$current_login = $_SESSION['login'];

// Извлечение изображения из базы данных
$stmt = $pdo->prepare("SELECT profile_picture_blob, profile_picture_type FROM users WHERE login = :login");
$stmt->execute(['login' => $current_login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && !empty($user['profile_picture_blob'])) {
    header("Content-Type: " . $user['profile_picture_type']);
    echo $user['profile_picture_blob'];
} else {
    // Отображение изображения по умолчанию
    $defaultImagePath = '../images/default_avatar.png';
    header("Content-Type: " . mime_content_type($defaultImagePath));
    readfile($defaultImagePath);
}
?>
