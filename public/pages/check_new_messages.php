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

// Проверяем новые сообщения
$query = "SELECT COUNT(*) AS new_messages FROM messages WHERE recipient_id = ? AND is_read = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$new_messages = 0;
if ($row = $result->fetch_assoc()) {
    $new_messages = (int)$row['new_messages'];
}

echo json_encode(['new_messages' => $new_messages]);
?>
