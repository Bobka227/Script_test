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

    // Создание файла лога ошибки
    $logFile = 'error_log.txt';
    ini_set('log_errors', 1);
    ini_set('error_log', $logFile);
    require __DIR__ . '/vendor/autoload.php';

    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
    use Endroid\QrCode\Label\Label;
    use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;

    if (class_exists(\Endroid\QrCode\QrCode::class)) {
        echo "Библиотека endroid/qr-code установлена и подключена успешно.";
    } else {
        echo "Библиотека endroid/qr-code не установлена или не подключена.";
    }

    try {
        // Данные конфигурации для подключения к базе данных
        $host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        $dbname = 'hoc3ablulex394pb';
        $username = 'emk2ggh76qbpq4ml';
        $password = 'lf9c0g2qky76la6x';

        // Устанавливаем соединение с базой данных
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
        if (!is_dir($qrCodeDir) && !mkdir($qrCodeDir, 0777, true) && !is_dir($qrCodeDir)) {
            throw new Exception("Не удалось создать директорию для QR-кодов: $qrCodeDir");
        }

        // Генерация QR-кодов для каждого рецепта
       try{
            foreach ($recipes as $recipe) {
    $result = Builder::create()
        ->data($recipe['pdf_link'])
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->build();

    $qrCodePath = $qrCodeDir . 'qr_code_' . $recipe['id'] . '.png';
    file_put_contents($qrCodePath, $result->getString());

    echo '<div class="qr-code">';
    echo '<h2>' . htmlspecialchars($recipe['name'], ENT_QUOTES, 'UTF-8') . '</h2>';
    echo '<img src="' . htmlspecialchars($qrCodePath, ENT_QUOTES, 'UTF-8') . '" alt="QR-код для ' . htmlspecialchars($recipe['name'], ENT_QUOTES, 'UTF-8') . '">';
    echo '</div>';

} } catch (Exception $e) {
    error_log("Ошибка: " . $e->getMessage());
    echo "Ошибка: " . $e->getMessage();
}

        echo "QR-коды успешно созданы и сохранены.";

    } catch (PDOException $e) {
        error_log("Ошибка PDO: " . $e->getMessage());
        echo "Ошибка соединения с базой данных";
    } catch (Exception $e) {
        error_log("Ошибка: " . $e->getMessage());
        echo "Ошибка: " . $e->getMessage();
    }
    ?>
</div>
</body>
</html>
