<?php

// Подключение автозагрузчика Composer
require 'vendor/autoload.php';
use Endroid\QrCode\QrCode;

// Настройки базы данных из переменных окружения
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

try {
    // Соединение с базой данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получение всех рецептов из базы данных
    $stmt = $pdo->query("SELECT id, name, pdf_link FROM recipes");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$recipes) {
        throw new Exception("Рецепты не найдены");
    }

    // Создание папки для QR-кодов, если она не существует
    $qrCodeDir = 'qr_codes/';
    if (!is_dir($qrCodeDir)) {
        mkdir($qrCodeDir, 0777, true);
    }

    // Генерация QR-кодов для каждого рецепта
    foreach ($recipes as $recipe) {
        $qrCode = new QrCode($recipe['pdf_link']);
        $qrCode->setSize(300);

        // Сохраняем QR-код в виде изображения с уникальным именем
        $qrCodePath = $qrCodeDir . 'qr_code_' . $recipe['id'] . '.png';
        $qrCode->writeFile($qrCodePath);
    }

    echo "QR-коды успешно созданы и сохранены.";

} catch (PDOException $e) {
    error_log("Ошибка PDO: " . $e->getMessage());
    echo "Ошибка соединения с базой данных";
} catch (Exception $e) {
    error_log("Ошибка: " . $e->getMessage());
    echo "Ошибка: " . $e->getMessage();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);