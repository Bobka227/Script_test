<?php
header('Content-Type: application/json'); // Указываем, что возвращаем JSON

// Настройки базы данных из переменных окружения
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

require 'vendor/autoload.php'; // Автозагрузка Composer
use Endroid\QrCode\QrCode;

$response = ['status' => 'error', 'message' => ''];

try {
    // Соединение с базой данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получение всех рецептов из базы данных
    $stmt = $pdo->query("SELECT id, name, pdf_link FROM recipes");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Проверка и создание директории для QR-кодов
    $qrCodeDir = 'qr_codes';
    if (!is_dir($qrCodeDir)) {
        mkdir($qrCodeDir, 0777, true);
    }

    $recipeData = [];

    // Генерация QR-кодов и подготовка данных для ответа
    foreach ($recipes as $recipe) {
        try {
            // Генерация QR-кода
            $qrCode = new QrCode($recipe['pdf_link']);
            $qrCode->setSize(300);

            // Сохранение QR-кода в виде изображения
            $qrCodePath = $qrCodeDir . '/qr_code_' . $recipe['id'] . '.png';
            $qrCode->writeFile($qrCodePath);

            // Добавляем данные в ответ
            $recipeData[] = [
                'id' => $recipe['id'],
                'name' => htmlspecialchars($recipe['name'], ENT_QUOTES, 'UTF-8'),
                'qr_code' => $qrCodePath
            ];

        } catch (Exception $e) {
            error_log("Ошибка при генерации QR-кода для рецепта с ID " . $recipe['id'] . ": " . $e->getMessage());
        }
    }

    // Формирование ответа
    $response['status'] = 'success';
    $response['data'] = $recipeData;

} catch (PDOException $e) {
    $response['message'] = "Ошибка подключения к базе данных: " . $e->getMessage();
}

$jsonResponse = json_encode($response, JSON_UNESCAPED_UNICODE);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('Ошибка JSON: ' . json_last_error_msg());
    echo json_encode(['status' => 'error', 'message' => 'Ошибка обработки данных.']);
} else {
    echo $jsonResponse;
}

// Включаем отображение ошибок для отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);