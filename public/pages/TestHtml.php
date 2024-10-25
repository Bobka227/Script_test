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

    // Подключение автозагрузчика Composer
    require 'vendor/autoload.php';
    use Endroid\QrCode\QrCode;

    $host = getenv('s554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com');
    $dbname = getenv('Dhoc3ablulex394pb');
    $username = getenv('emk2ggh76qbpq4ml');
    $password = getenv('lf9c0g2qky76la6x');
    try {
        // Проверяем наличие переменных окружения
        if (!$host || !$dbname || !$username || !$password) {
            throw new Exception('Проверьте переменные окружения.');
        }


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

            // Выводим QR-код и название рецепта на страницу
            echo '<div class="qr-code">';
            echo '<h2>' . htmlspecialchars($recipe['name']) . '</h2>';
            echo '<img src="' . htmlspecialchars($qrCodePath) . '" alt="QR-код для ' . htmlspecialchars($recipe['name']) . '">';
            echo '</div>';
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
