<?php
header('Content-Type: application/json');

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL-запрос для получения данных рецептов и эмоций
    $stmt = $pdo->query("
        SELECT recipes.name AS title, recipes.image AS img, recipe_emotion.emotion_id 
        FROM recipes 
        JOIN recipe_emotion ON recipes.id = recipe_emotion.recipe_id
    ");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Формируем структуру данных для JSON
    $foodMoodData = [
        'first' => [],
        'left' => [],
        'right' => []
    ];

    $moodMapping = [
        1 => 'first',
        2 => 'left',
        3 => 'right',
        // Добавьте дополнительные эмоции по мере необходимости
    ];

    foreach ($recipes as $row) {
        $mood = $moodMapping[$row['emotion_id']] ?? 'first'; // Используйте 'first' по умолчанию
        $foodMoodData[$mood][] = [
            'title' => $row['title'],
            'img' => $row['img']
        ];
    }

    echo json_encode($foodMoodData);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
