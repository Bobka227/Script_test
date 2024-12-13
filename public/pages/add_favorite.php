<?php
session_start();
require '../../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$favorite_id = isset($_POST['favorite_id']) ? (int)$_POST['favorite_id'] : null;

if ($favorite_id) {
    // Проверяем, есть ли пользователь уже в избранном
    $query = "SELECT * FROM favorites WHERE user_id = ? AND favorite_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $favorite_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Если есть, удаляем из избранного
        $query = "DELETE FROM favorites WHERE user_id = ? AND favorite_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $favorite_id);
        $stmt->execute();
    } else {
        // Если нет, добавляем в избранное
        $query = "INSERT INTO favorites (user_id, favorite_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $favorite_id);
        $stmt->execute();
    }

    header('Location: chat.php');
    exit();
} else {
    die('Неверный запрос.');
}
?>
