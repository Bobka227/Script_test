<?php
header('Content-Type: application/json');

$host = '185.22.67.9';
$dbname = 'anya';
$username = 'anya';
$password = 'anya';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $emotionId = isset($_GET['emotion_id']) ? (int)$_GET['emotion_id'] : 0;

    // SQL-запрос для получения данных рецептов, фильтрованных по emotion_id
    $query = "
        SELECT recipes.name AS title, recipes.image AS img, recipe_emotion.emotion_id 
        FROM recipes 
        JOIN recipe_emotion ON recipes.id = recipe_emotion.recipe_id
    ";

    if ($emotionId > 0) {
        $query .= " WHERE recipe_emotion.emotion_id = :emotionId";
    }

    $stmt = $pdo->prepare($query);

    if ($emotionId > 0) {
        $stmt->bindParam(':emotionId', $emotionId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Формируем структуру данных для JSON
    $foodMoodData = [];

    foreach ($recipes as $row) {
        $foodMoodData[] = [
            'title' => $row['title'],
            'img' => $row['img']
        ];
    }

    echo json_encode($foodMoodData);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>