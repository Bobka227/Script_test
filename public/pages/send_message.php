<?php
session_start();
require '../../config.php'; // Подключаем файл с настройками

// Проверяем сессию
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    die('Сессия потеряна. Войдите снова.');
}

$sender_id = $_SESSION['user_id'];

// Проверяем данные формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_id'], $_POST['message'])) {
    $recipient_id = (int)$_POST['recipient_id'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        die('Сообщение не может быть пустым.');
    }

    // Вставляем сообщение в базу
    $created_at = date('Y-m-d H:i:s'); // Альтернатива для NOW()
    $query = "INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $sender_id, $recipient_id, $message, $created_at);

    if ($stmt->execute()) {
        header('Location: chat.php?user=' . $recipient_id); // Перенаправляем на чат с конкретным пользователем
        exit();
    } else {
        die('Ошибка сохранения сообщения: ' . $stmt->error);
    }
} else {
    die('Неверные данные формы.');
}
?>
