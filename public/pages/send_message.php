<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$recipient_id = $_POST['recipient_id'];
$message = $_POST['message'];

// Вставляем сообщение в базу данных
$query = "INSERT INTO messages (sender_id, recipient_id, message) VALUES ('$user_id', '$recipient_id', '$message')";
pg_query($dbconn, $query);

header('Location: chat.php');
