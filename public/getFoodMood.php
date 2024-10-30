<?php
header('Content-Type: application/json');

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $emotionId = isset($_GET['emotion_id']) ? (int)$_GET['emotion_id'] : 0;
    $time = isset($_GET['time']) ? $_GET['time'] : '';

    // SQL query to get recipe data filtered by emotion_id and time
    $query = "
        SELECT recipes.name AS title, recipes.image AS img, recipe_emotion.emotion_id, recipes.time 
        FROM recipes 
        JOIN recipe_emotion ON recipes.id = recipe_emotion.recipe_id
    ";

    $conditions = [];
    if ($emotionId > 0) {
        $conditions[] = "recipe_emotion.emotion_id = :emotionId";
    }
    if (!empty($time)) {
        $conditions[] = "recipes.time = :time";
    }

    if (count($conditions) > 0) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt = $pdo->prepare($query);

    if ($emotionId > 0) {
        $stmt->bindParam(':emotionId', $emotionId, PDO::PARAM_INT);
    }
    if (!empty($time)) {
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
    }

    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $foodMoodData = [];
    foreach ($recipes as $row) {
        $foodMoodData[] = [
            'title' => $row['title'],
            'img' => $row['img'],
            'time' => $row['time']
        ];
    }

    echo json_encode($foodMoodData);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>