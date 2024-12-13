<?php
session_start();
require '../../config.php'; // Подключаем файл с настройками и подключением

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    header('Location: register.php'); // Перенаправление, если пользователь не авторизован
    exit();
}

// Получаем ID текущего пользователя из базы данных
if (!isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];

    $query = "SELECT id FROM users WHERE LOWER(username) = LOWER(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id']; // Сохраняем user_id в сессию
    } else {
        die('Пользователь не найден');
    }
}

// Устанавливаем ID текущего пользователя
$sender_id = $_SESSION['user_id'];

// Проверяем данные формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_id'], $_POST['message'])) {
    $recipient_id = (int)$_POST['recipient_id'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        die('Сообщение не может быть пустым.');
    }

    // Сохраняем сообщение в базе данных
    $query = "INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $sender_id, $recipient_id, $message);

    if ($stmt->execute()) {
        header('Location: chat.php'); // Перенаправление обратно в чат
        exit();
    } else {
        die('Ошибка сохранения сообщения: ' . $stmt->error);
    }
} else {
    die('Неверные данные формы.');
}
?>
