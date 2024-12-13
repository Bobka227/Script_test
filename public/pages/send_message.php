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
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка сохранения сообщения']);
        exit();
    }

}
?>