<?php
header('Content-Type: application/json');

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получаем emotion_id из параметров запроса
    $selectedEmotion = $_GET['emotion'] ?? null;
    $moodMapping = [
        'sad' => 1,
        'happy' => 2,
        'fear' => 3,
        'disgust' => 4,
        'inspired' => 5,
        'merry' => 6,
        'lost' => 7,
        'calm' => 8,
        'angry' => 9,
        'horny' => 10,
    ];

    // Проверяем, существует ли запрошенная эмоция
    if ($selectedEmotion && isset($moodMapping[$selectedEmotion])) {
        $emotionId = $moodMapping[$selectedEmotion];
        file_put_contents('debug_log.txt', "Полученная эмоция: " . ($selectedEmotion ?? 'null') . PHP_EOL, FILE_APPEND);
        // Запрос только для заданной эмоции
        $stmt = $pdo->prepare("
            SELECT recipes.name AS title, recipes.image AS img 
            FROM recipes 
            JOIN recipe_emotion ON recipes.id = recipe_emotion.recipe_id
            WHERE recipe_emotion.emotion_id = :emotion_id
        ");
        $stmt->execute(['emotion_id' => $emotionId]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($recipes);
    } else {
        echo json_encode(['error' => 'Invalid emotion']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
