<?php
session_start();

// Подключение к базе данных
$servername = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$username = 'wk4kwaf4w8x12twh';
$password = 'ijw8uyd2lwkgf8on';

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение логина пользователя из сессии
$user_id = $_SESSION['user_id']; // Предполагается, что идентификатор пользователя хранится в сессии
$sql = "SELECT login FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Вывод логина пользователя
    $row = $result->fetch_assoc();
    echo $row['login'];
} else {
    echo "No user found";
}

$conn->close();
?>