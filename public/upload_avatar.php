<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$username = $_SESSION['username'];

// Подключение к базе данных
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$db_username = 'emk2ggh76qbpq4ml';
$db_password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Отладочный вывод удалён
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных: ' . $e->getMessage()]);
    exit();
}

// Проверяем, был ли загружен файл
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] != UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'File upload error']);
    exit();
}

// Проверяем тип файла
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    exit();
}

// Проверяем размер файла (не более 2 МБ)
$maxFileSize = 2 * 1024 * 1024;
if ($_FILES['avatar']['size'] > $maxFileSize) {
    echo json_encode(['status' => 'error', 'message' => 'File size exceeds limit']);
    exit();
}

// Генерируем уникальное имя файла
$extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
$extension = strtolower($extension); // Приводим расширение к нижнему регистру
$newFileName = 'avatar_' . $username . '_' . time() . '.' . $extension;

// Указываем директорию для сохранения аватарок
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
        exit();
    }
}

$uploadFilePath = $uploadDir . $newFileName;

// Перемещаем загруженный файл
if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFilePath)) {
    // Обновляем путь к аватарке в базе данных
    $avatarPath = 'uploads/' . $newFileName; // Относительный путь

    $stmt = $pdo->prepare("UPDATE users SET profile_picture = :avatar WHERE username = :username");
    $stmt->execute(['avatar' => $avatarPath, 'username' => $username]);

    echo json_encode(['status' => 'success', 'new_avatar_path' => '../' . $avatarPath]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error saving file']);
}
?>
