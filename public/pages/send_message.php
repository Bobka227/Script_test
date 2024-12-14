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

$sender_id = $_SESSION['user_id'];

// Проверяем данные формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_id'], $_POST['message'])) {
    $recipient_id = (int)$_POST['recipient_id'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Message cannot be empty']);
        exit();
    }

    // Шифруем сообщение
    $encrypted_message = encrypt_message($message, $encryption_key);

    // Вставляем сообщение в базу данных
    $created_at = date('Y-m-d H:i:s');
    $query = "INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $sender_id, $recipient_id, $encrypted_message, $created_at);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save message']);
        exit();
    }
}

// Функция для шифрования сообщения
function encrypt_message($message, $key) {
    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($message, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted); // Сохраняем IV вместе с шифротекстом
}
?>
