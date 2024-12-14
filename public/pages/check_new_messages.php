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

try {
    // SQL-запрос для получения user_id по username
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];

        // SQL-запрос для проверки новых сообщений
        $queryMessages = "
            SELECT COUNT(*) as new_messages
            FROM messages
            WHERE recipient_id = ? AND is_read = 0
        ";
        $stmtMessages = $conn->prepare($queryMessages);
        $stmtMessages->bind_param("i", $user_id);
        $stmtMessages->execute();
        $resultMessages = $stmtMessages->get_result();

        if ($rowMessages = $resultMessages->fetch_assoc()) {
            $new_messages = $rowMessages['new_messages'];
            echo json_encode(['new_messages' => $new_messages]);
        } else {
            echo json_encode(['new_messages' => 0]);
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
}
?>
