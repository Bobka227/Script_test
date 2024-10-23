<?php
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

// Создаем соединение с базой данных
$conn = new mysqli($host, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Получаем фильтр из GET-запроса
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Подготавливаем SQL-запрос для выборки рецептов по фильтру
$sql = "SELECT name, image, cooking_method, time_required FROM recipes WHERE type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $filter);  // Привязываем фильтр
$stmt->execute();
$result = $stmt->get_result();

// Массив для хранения данных рецептов
$recipes = [];

if ($result->num_rows > 0) {
    // Собираем рецепты в массив
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}

// Возвращаем данные в формате JSON
header('Content-Type: application/json');
echo json_encode($recipes);

// Закрываем соединение
$stmt->close();
$conn->close();



