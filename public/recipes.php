<?php
// Включаем отображение ошибок (для отладки)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

$conn = new mysqli($host, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    error_log("Ошибка соединения с базой данных: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
} else {
    error_log("Успешное подключение к базе данных.");
}

// Получаем тип рецепта и поисковый запрос из GET-запроса, если они есть
$type = isset($_GET['type']) ? $_GET['type'] : '';
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Валидация типа (например, только разрешенные значения)
$valid_types = ['spicy', 'vegan', 'vegetarian', 'quick', 'no oven', 'drinks', 'sweet', 'grilled', 'soups', 'Italian', 'Czech', 'Kazakh', 'Ukrainian', 'Asian'];
if ($type && !in_array($type, $valid_types)) {
    error_log("Неверный тип рецепта: " . $type);
    die("Invalid recipe type");
}

// Формируем SQL-запрос с фильтрацией по типу рецепта и текстовому запросу
$sql_query = "SELECT * FROM recipes WHERE 1=1";
$params = [];
$types = '';

if ($type) {
    $sql_query .= " AND type = ?";
    $params[] = $type;
    $types .= 's';
}

if ($query) {
    $sql_query .= " AND (name LIKE ? OR name  LIKE ?)";
    $query_param = '%' . $query . '%';
    $params[] = $query_param;
    $params[] = $query_param;
    $types .= 'ss';
}

// Подготавливаем SQL-запрос
$sql = $conn->prepare($sql_query);

if ($params) {
    $sql->bind_param($types, ...$params);
}

// Выполняем запрос
$sql->execute();
$result = $sql->get_result();

$recipes = [];

if ($result->num_rows > 0) {
    error_log("Найдено рецептов: " . $result->num_rows);
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
} else {
    error_log("Рецепты не найдены.");
}

// Отправляем ответ в формате JSON
header('Content-Type: application/json');
echo json_encode($recipes);

// Закрытие подготовленного запроса и соединения
$sql->close();
$conn->close();
?>

