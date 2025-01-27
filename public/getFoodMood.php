<?php
header('Content-Type: application/json');
session_start(); // Используем сессии для отслеживания

// Подключение к базе данных
$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$username = 'wk4kwaf4w8x12twh';
$password = 'ijw8uyd2lwkgf8on';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получаем emotion_id из GET-запроса
    $emotionId = isset($_GET['emotion_id']) ? (int)$_GET['emotion_id'] : 0;

    // Если emotion_id некорректный
    if ($emotionId <= 0) {
        echo json_encode(['error' => 'Invalid emotion ID.']);
        exit;
    }

    // Инициализация данных для текущей эмоции
    if (!isset($_SESSION['shown_recipes'])) {
        $_SESSION['shown_recipes'] = [];
    }
    if (!isset($_SESSION['shown_recipes'][$emotionId])) {
        $_SESSION['shown_recipes'][$emotionId] = [
            'recipes' => [], // ID уже показанных рецептов
            'count' => 0     // Количество перелистываний
        ];
    }

    // Проверяем, достигнуто ли максимальное количество перелистываний
    if ($_SESSION['shown_recipes'][$emotionId]['count'] >= 3) {
        echo json_encode(['error' => 'You have reached the maximum number of views for this emotion.']);
        exit;
    }

    // Получаем список уже показанных рецептов
    $shownRecipes = $_SESSION['shown_recipes'][$emotionId]['recipes'];

    // SQL-запрос: случайный выбор блюд, исключая уже показанные
    $query = "
        SELECT recipes.id, recipes.name AS title, recipes.image AS img, recipes.time 
        FROM recipes 
        JOIN recipe_emotion ON recipes.id = recipe_emotion.recipe_id
        WHERE recipe_emotion.emotion_id = :emotionId
          AND recipes.id NOT IN (" . (count($shownRecipes) > 0 ? implode(',', $shownRecipes) : "NULL") . ")
        ORDER BY RAND()
        LIMIT 3
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':emotionId', $emotionId, PDO::PARAM_INT);
    $stmt->execute();

    // Извлекаем данные
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Если блюд меньше 3, значит больше нечего показывать
    if (count($recipes) < 3) {
        echo json_encode(['error' => 'Not enough recipes to display.']);
        exit;
    }

    // Увеличиваем счетчик перелистываний
    $_SESSION['shown_recipes'][$emotionId]['count']++;

    // Добавляем ID показанных рецептов в список
    foreach ($recipes as $recipe) {
        $_SESSION['shown_recipes'][$emotionId]['recipes'][] = $recipe['id'];
    }

    // Возвращаем результат
    echo json_encode($recipes);
} catch (PDOException $e) {
    // Обработка ошибок
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
