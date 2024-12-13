<?php
session_start();

if (!isset($_SESSION['login'])) {
    exit("Unauthorized access");
}

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$db_username = 'emk2ggh76qbpq4ml';
$db_password = 'lf9c0g2qky76la6x';

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
