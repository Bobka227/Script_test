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
$recipient_id = $_GET['recipient_id'] ?? null;

if (!$recipient_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid recipient ID']);
    exit();
}

$query = "
    SELECT m.id, m.message, m.is_read, m.created_at, 
           CASE WHEN m.sender_id = ? THEN 'You' ELSE u.username END AS sender
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE (m.sender_id = ? AND m.recipient_id = ?) 
       OR (m.sender_id = ? AND m.recipient_id = ?)
    ORDER BY m.created_at ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiiii", $user_id, $user_id, $recipient_id, $recipient_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'id' => $row['id'],
        'message' => $row['message'],
        'is_read' => $row['is_read'],
        'created_at' => $row['created_at'],
        'sender' => $row['sender']
    ];
}

echo json_encode($messages);
?>
