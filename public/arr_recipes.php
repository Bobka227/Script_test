<?php
// Подключение к базе данных
include 'db_connection.php';

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

$conn = new mysqli($host, $username, $password, $dbname);

$emotion_id = isset($_GET['emotion_id']) ? intval($_GET['emotion_id']) : 0;

if ($emotion_id === 0) {
    echo json_encode([]);
    exit;
}

// SQL-запрос для получения данных
$query = "
    SELECT 
        time AS meal_type,
        name AS title,
        image AS img
    FROM recipes
    WHERE emotion_id = $emotion_id
    ORDER BY meal_type
";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode([]);
    exit;
}

// Группировка данных по типам приема пищи
$menuVariants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $mealType = $row['meal_type']; // breakfast, lunch, dinner
    $menuVariants[$mealType][] = [
        'title' => $row['title'],
        'img' => $row['img']
    ];
}

// Формирование 3 вариантов меню
$finalData = [];
for ($i = 0; $i < 3; $i++) {
    $variant = [
        'breakfast' => $menuVariants['breakfast'][$i] ?? null,
        'lunch' => $menuVariants['lunch'][$i] ?? null,
        'dinner' => $menuVariants['dinner'][$i] ?? null,
    ];
    $finalData[] = $variant;
}

// Возврат данных в формате JSON
echo json_encode($finalData);
