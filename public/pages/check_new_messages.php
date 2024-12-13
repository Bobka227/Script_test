<?php
session_start();
require '../../config.php'; // Подключение к базе данных

header('Content-Type: application/json');

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение новых сообщений
$query = "
    SELECT m.id, m.message, m.created_at, u.username AS sender
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.recipient_id = ? AND m.is_read = 0
    ORDER BY m.created_at ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'id' => $row['id'],
        'text' => $row['message'],
        'sender' => $row['sender'],
        'created_at' => $row['created_at']
    ];
}

// Пометка сообщений как прочитанных
if (!empty($messages)) {
    $message_ids = array_column($messages, 'id');
    $placeholders = implode(',', array_fill(0, count($message_ids), '?'));
    $query = "UPDATE messages SET is_read = 1 WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($query);

    $types = str_repeat('i', count($message_ids));
    $stmt->bind_param($types, ...$message_ids);
    $stmt->execute();
}

// Возвращаем результат
echo json_encode([
    'new_messages' => count($messages),
    'messages' => $messages
]);
?>
