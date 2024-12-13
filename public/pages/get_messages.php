<?php
session_start();
require '../../config.php'; // Подключение к базе данных

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die(json_encode(['error' => 'Unauthorized']));
}

$user_id = $_SESSION['user_id'];
$recipient_id = isset($_GET['recipient_id']) ? (int)$_GET['recipient_id'] : null;

if (!$recipient_id) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid recipient ID']));
}

// Получаем сообщения
$query = "SELECT m.message, m.created_at, 
                 CASE WHEN m.sender_id = ? THEN 'You' ELSE u.username END AS sender
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          WHERE (m.sender_id = ? AND m.recipient_id = ?)
             OR (m.sender_id = ? AND m.recipient_id = ?)
          ORDER BY m.created_at ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiiii", $user_id, $user_id, $recipient_id, $recipient_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Помечаем сообщения как прочитанные
$query = "UPDATE messages SET is_read = 1 WHERE recipient_id = ? AND sender_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $recipient_id);
$stmt->execute();


header('Content-Type: application/json');
echo json_encode($messages);
?>
