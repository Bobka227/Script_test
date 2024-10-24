<?php
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

require 'vendor/autoload.php';
use Endroid\QrCode\QrCode;

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

    // Генерация QR-кодов для каждого рецепта
    foreach ($recipes as $recipe) {
        try {
            $qrCode = new QrCode($recipe['pdf_link']);
            $qrCode->setSize(300);

            // Сохранение QR-кода в виде изображения
            $qrCodePath = $qrCodeDir . '/qr_code_' . $recipe['id'] . '.png';
            $qrCode->writeFile($qrCodePath);
        } catch (Exception $e) {
            echo "Ошибка при генерации QR-кода для рецепта с ID " . $recipe['id'] . ": " . $e->getMessage();
        }
    }

} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>