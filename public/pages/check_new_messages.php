<?php
session_start();
require '../../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение количества новых сообщений
$query = "SELECT COUNT(*) AS new_messages FROM messages WHERE recipient_id = ? AND is_read = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$new_messages = $result->fetch_assoc()['new_messages'] ?? 0;
echo json_encode(['new_messages' => (int)$new_messages]);
?>