<?php
session_start();
require '../../config.php';

header('Content-Type: application/json');

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Получаем ключ шифрования из переменной окружения
$encryption_key = getenv('ENCRYPTION_KEY');
if (!$encryption_key) {
    http_response_code(500);
    echo json_encode(['error' => 'Encryption key is not set']);
    exit();
}

$user_id = $_SESSION['user_id'];
$recipient_id = $_GET['recipient_id'] ?? null;

if (!$recipient_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid recipient ID']);
    exit();
}

// Подготавливаем SQL-запрос для получения сообщений
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
        'message' => decrypt_message($row['message'], $encryption_key),
        'is_read' => $row['is_read'],
        'created_at' => $row['created_at'],
        'sender' => $row['sender']
    ];
}

echo json_encode($messages);

// Функция для расшифровки сообщения
function decrypt_message($encrypted_message, $key) {
    $data = base64_decode($encrypted_message);
    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($data, 0, $iv_length);
    $encrypted = substr($data, $iv_length);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}
?>
