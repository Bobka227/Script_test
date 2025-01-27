<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit();
}

// Подключение к базе данных
$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$db_username = 'wk4kwaf4w8x12twh';
$db_password = 'ijw8uyd2lwkgf8on';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных']);
    exit();
}

// Проверяем, загружен ли файл
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $avatar = $_FILES['avatar'];

    // Проверяем наличие ошибок при загрузке
    if ($avatar['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки файла']);
        exit();
    }

    // Проверяем тип файла
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($avatar['type'], $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Недопустимый тип файла. Разрешены только JPG, PNG и GIF']);
        exit();
    }

    // Читаем содержимое файла и изменяем размер
    $source_image = null;
    switch ($avatar['type']) {
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($avatar['tmp_name']);
            break;
        case 'image/png':
            $source_image = imagecreatefrompng($avatar['tmp_name']);
            break;
        case 'image/gif':
            $source_image = imagecreatefromgif($avatar['tmp_name']);
            break;
    }

    if (!$source_image) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка обработки изображения']);
        exit();
    }

    // Получаем размеры оригинального изображения
    $original_width = imagesx($source_image);
    $original_height = imagesy($source_image);

    // Вычисляем новые пропорции
    $new_width = 800;
    $new_height = intval(($original_height / $original_width) * $new_width);

    // Создаем пустое изображение нового размера
    $resized_image = imagecreatetruecolor($new_width, $new_height);

    // Копируем и изменяем размер исходного изображения
    imagecopyresampled(
        $resized_image,
        $source_image,
        0, 0, 0, 0,
        $new_width, $new_height,
        $original_width, $original_height
    );

    // Сохраняем изображение в формате JPEG с качеством 90
    ob_start();
    imagejpeg($resized_image, null, 90);
    $jpeg_image = ob_get_clean();

    // Освобождаем память
    imagedestroy($source_image);
    imagedestroy($resized_image);

    // Сохраняем данные в базе данных
    $stmt = $pdo->prepare("UPDATE users SET profile_picture_blob = :image, profile_picture_type = :type WHERE username = :username");
    $stmt->execute([
        'image' => $jpeg_image,
        'type' => 'image/jpeg',
        'username' => $_SESSION['username']
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Изображение успешно загружено']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Файл не был загружен']);
}
?>
