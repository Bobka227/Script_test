<?php
session_start();
require '../../config.php'; // Подключение к базе данных или конфигу

header('Content-Type: application/json');

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Получаем ID пользователя
$user_id = $_SESSION['user_id'];

// SQL-запрос для проверки новых сообщений
$query = "
    SELECT COUNT(*) as new_messages
    FROM messages
    WHERE recipient_id = ? AND is_read = 0
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Проверяем результат запроса
if ($row = $result->fetch_assoc()) {
    $new_messages = $row['new_messages'];
    echo json_encode(['new_messages' => $new_messages]);
} else {
    echo json_encode(['new_messages' => 0]);
}
?>
