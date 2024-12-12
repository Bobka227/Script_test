<?php
header('Content-Type: application/json');

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получение параметра emotion_id из запроса
    $emotion_id = isset($_GET['emotion_id']) ? intval($_GET['emotion_id']) : 0;

    if ($emotion_id > 0) {
        // Запрос на выборку блюд, связанных с данной эмоцией
        $stmt = $pdo->prepare("
            SELECT r.id, r.name, r.time, r.image, r.qr_code_link, r.pdf_link
            FROM recipes r
            JOIN recipe_emotion re ON r.id = re.recipe_id
            WHERE re.emotion_id = :emotion_id
        ");
        $stmt->execute(['emotion_id' => $emotion_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedData = [];
foreach ($results as $recipe) {
    $groupedData[$recipe['time']][] = [
        'id' => $recipe['id'],
        'name' => $recipe['name'],
        'image' => $recipe['image'], // Добавлено поле image
        'qr_code_link' => $recipe['qr_code_link'], // Добавлено поле qr_code
        'pdf_link' => $recipe['pdf_link'] // Добавлено поле pdf
    ];
}

        // Подготовка трех вариантов меню
        $variants = [];
        for ($i = 0; $i < 3; $i++) {
            $variant = [];
            foreach (['breakfast', 'lunch', 'dinner'] as $mealTime) {
                $variant[$mealTime] = $groupedData[$mealTime][$i % count($groupedData[$mealTime])] ?? null;
            }
            $variants[] = $variant;
        }

        echo json_encode($variants);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
