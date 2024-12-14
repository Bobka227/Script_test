<?php
session_start();
require '../../config.php'; // Подключение к базе данных

header('Content-Type: application/json');

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Получаем username текущего пользователя
$username = $_SESSION['username'];

// Подготовка SQL-запроса для проверки новых сообщений
$query = "
    SELECT COUNT(*) as new_messages
    FROM messages
    WHERE recipient_id = ? AND is_read = 0
";

try {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Используем строковый параметр
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $new_messages = $row['new_messages'];
        echo json_encode(['new_messages' => $new_messages]);
    } else {
        echo json_encode(['new_messages' => 0]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
}
?>
