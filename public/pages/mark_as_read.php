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

// Чтение и декодирование входных данных
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Логирование входных данных для отладки
error_log('Received mark_as_read data: ' . print_r($data, true));

// Проверяем наличие и корректность message_ids
if (!isset($data['message_ids'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request: message_ids is missing']);
    exit();
}

$message_ids = $data['message_ids'];

// Проверяем, что message_ids содержит массив и не пуст
if (empty($message_ids) || !is_array($message_ids)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid request: message_ids must be a non-empty array',
        'received_data' => $data // Для отладки
    ]);
    exit();
}

// Убедитесь, что все message_ids являются числами
$message_ids = array_map('intval', $message_ids);

// Генерация плейсхолдеров для запроса
$placeholders = implode(',', array_fill(0, count($message_ids), '?'));
$query = "UPDATE messages SET is_read = 1 WHERE id IN ($placeholders) AND recipient_id = ?";

// Подготовка запроса
$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

// Генерация типов для bind_param
$types = str_repeat('i', count($message_ids)) . 'i'; // 'i' для каждого ID и для recipient_id

// Объединение message_ids и user_id в один массив
$params = array_merge($message_ids, [$user_id]);

// Привязка параметров
$stmt->bind_param($types, ...$params);

// Выполнение запроса
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to execute query: ' . $stmt->error]);
    exit();
}

// Закрытие запроса
$stmt->close();

// Возвращаем успешный ответ
echo json_encode(['success' => true]);
?>
