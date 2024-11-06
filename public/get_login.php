<?php
session_start();

// Подключение к базе данных
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

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