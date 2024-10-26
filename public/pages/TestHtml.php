<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рецепты с QR-кодами</title>
    <style>
        .qr-code {
            margin: 20px;
            display: inline-block;
        }
        .qr-code img {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body>
<h1>Рецепты с QR-кодами</h1>
<div id="qr-codes">
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require __DIR__ . '/../../vendor/autoload.php';

    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\Writer\PngWriter;
    use Endroid\QrCode\Color\Color;
    use Endroid\QrCode\Encoding\Encoding;

    // Конфигурация для подключения к базе данных
    $host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
    $dbname = 'hoc3ablulex394pb';
    $username = 'emk2ggh76qbpq4ml';
    $password = 'lf9c0g2qky76la6x';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Получение всех рецептов из базы данных
        $stmt = $pdo->query("SELECT id, name, pdf_link FROM recipes");
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $qrCodeDir = 'qr_codes/';
        if (!is_dir($qrCodeDir)) {
            mkdir($qrCodeDir, 0777, true);
        }

        foreach ($recipes as $recipe) {
            // Создаем QR-код для каждого рецепта
            $qrCode = new QrCode(
                data: $recipe['pdf_link'],                    // Данные для кодирования, например, PDF-ссылка
                encoding: new Encoding('UTF-8'),                                     // Размер QR-кода
                size: 300,                                    // Отступ
                margin: 10,               // Кодировка
                foregroundColor: new Color(0, 0, 0),           // Цвет QR-кода
                backgroundColor: new Color(255, 255, 255)      // Цвет фона
            );

            // Используем PngWriter для генерации изображения
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Сохраняем QR-код в файл
            $filePath = $qrCodeDir . 'qr_code_' . $recipe['id'] . '.png';
            file_put_contents($filePath, $result->getString());

            // Отображаем изображение на странице
            echo "<div class='qr-code'>";
            echo "<h2>{$recipe['name']}</h2>";
            echo "<img src='$filePath' alt='QR-код для {$recipe['name']}'>";
            echo "</div>";
        }

    } catch (PDOException $e) {
        error_log("Ошибка соединения с БД: " . $e->getMessage());
        echo "Ошибка соединения с базой данных.";
    } catch (Exception $e) {
        error_log("Ошибка: " . $e->getMessage());
        echo "Ошибка: " . $e->getMessage();
    }
    ?>
</div>
</body>
</html>
