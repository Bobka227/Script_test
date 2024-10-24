<?php
// Настройки базы данных
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

require 'vendor/autoload.php'; // Автозагрузка Composer
use Endroid\QrCode\QrCode;

try {
    // Соединение с базой данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Соединение с базой данных успешно установлено!<br>";

    // Получение всех рецептов из базы данных
    $stmt = $pdo->query("SELECT id, name, pdf_link FROM recipes");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Рецепты получены:<br>";
    foreach ($recipes as $recipe) {
        echo "ID: " . $recipe['id'] . ", Название: " . $recipe['name'] . "<br>";
    }

    // Проверка и создание директории для QR-кодов
    $qrCodeDir = 'qr_codes';
    if (!is_dir($qrCodeDir)) {
        mkdir($qrCodeDir, 0777, true);
        echo "Директория для QR-кодов создана.<br>";
    } else {
        echo "Директория для QR-кодов уже существует.<br>";
    }

    // Генерация QR-кодов для каждого рецепта
    foreach ($recipes as $recipe) {
        try {
            $qrCode = new QrCode($recipe['pdf_link']);
            $qrCode->setSize(300);

            // Сохранение QR-кода в виде изображения
            $qrCodePath = $qrCodeDir . '/qr_code_' . $recipe['id'] . '.png';
            $qrCode->writeFile($qrCodePath);

            echo "QR-код для рецепта " . $recipe['name'] . " успешно создан и сохранён в " . $qrCodePath . "<br>";

        } catch (Exception $e) {
            echo "Ошибка при генерации QR-кода для рецепта с ID " . $recipe['id'] . ": " . $e->getMessage() . "<br>";
        }
    }

} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage() . "<br>";
}

// Включаем отображение ошибок для отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

