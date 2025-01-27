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
    <link rel="stylesheet" href="../styles/menu.css">
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
    $host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
    $dbname = 'nb6x6m9qlsec07j8';
    $username = 'wk4kwaf4w8x12twh';
    $password = 'ijw8uyd2lwkgf8on';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Получение всех рецептов из базы данных
        $stmt = $pdo->query("SELECT id, name, pdf_link FROM recipes");
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($recipes as $recipe) {
            // Создаем QR-код для каждого рецепта
            $qrCode = new QrCode(
                data: $recipe['pdf_link'],
                encoding: new Encoding('UTF-8'),
                size: 300,
                margin: 10,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );

            // Используем PngWriter для генерации изображения
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Получаем QR-код как строку в формате PNG
            $qrCodeData = $result->getString();

            // Кодируем строку в base64 для хранения в базе данных
            $qrCodeBase64 = base64_encode($qrCodeData);

            // Сохраняем QR-код в базе данных
            $updateStmt = $pdo->prepare("UPDATE recipes SET qr_code_link = :qrCodeLink WHERE id = :recipeId");
            $updateStmt->bindParam(':qrCodeLink', $qrCodeBase64);
            $updateStmt->bindParam(':recipeId', $recipe['id']);
            $updateStmt->execute();

            // Отображаем изображение на странице
            echo "<div class='qr-code'>";
            echo "<h2>{$recipe['name']}</h2>";
            echo "<img src='data:image/png;base64,{$qrCodeBase64}' alt='QR-код для {$recipe['name']}'>";
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

<footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h5>About FoodMood</h5>
                    <p>FoodMood is your personal food assistant, helping you explore new recipes and customize your meal plans according to your mood. Discover delicious recipes, whether you're happy, sad, or anything in between!</p>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="../index_startPage.html">Main Page</a></li>
                        <li><a href="register.php">Sign In/Sign Up</a></li>
                        <li><a href="search.php">Food Recipes</a></li>
                        <li><a href="mood.php">Mood Recipes</a></li>
                        <li><a href="help.html">Help</a></li>
                    </ul>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Contact Us</h5>
                    <p>Email: <a href="mailto:support@FoodMood.com">support@FoodMood.com</a></p>
                    <p>Phone: +420 777 430 106</p>
                    <div class ="footer-social-links">
                        <a href="#"><img src="../images/footer/facebookdefault.svg" alt="Facebook"></a>
                        <a href="#"><img src="../images/footer/instadefault.svg" alt="Instagram"></a>
                        <a href="#"><img src="../images/footer/youtubedefault.svg" alt="YouTube"></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 FoodMood. All rights reserved.</p>
            </div>
        </div>
    </footer>

</div>
</body>
</html>
