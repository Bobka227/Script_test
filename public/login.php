<?php

// Параметры для подключения к базе данных
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    // Создание подключения к базе данных с использованием PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Установка режима обработки ошибок - выбрасывать исключения
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Подключение к базе данных успешно!<br>"; // Сообщение об успешном подключении (для отладки)
} catch (PDOException $e) {
    // Обработка ошибки подключения к базе данных
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}






function loginUser($pdo, $email, $password): string
{
    echo "Функция входа вызвана<br>"; // Сообщение для отладки

    // Подготовка SQL-запроса для поиска пользователя по email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    // Выполнение запроса с передачей параметра email
    $stmt->execute(['email' => $email]);

    // Проверка, найден ли пользователь с указанным email
    if ($stmt->rowCount() === 0) {
        echo "Пользователь не найден<br>"; // Сообщение для отладки
        return "Вход не успешен.";
    }

    // Получение данных пользователя из базы данных
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка соответствия пароля
    if (password_verify($password, $user['password'])) {
        echo "Пользователь успешно аутентифицирован<br>"; // Сообщение для отладки
        return "Вход успешен!";
    } else {
        echo "Неправильный пароль<br>"; // Сообщение для отладки
        return "Вход не успешен.";
    }
}

// Проверка, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST запрос получен<br>"; // Сообщение для отладки

    // Проверка, что все необходимые поля заполнены
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Вызов функции входа пользователя
        $result = loginUser($pdo, $email, $password);
        echo $result; // Вывод результата входа
    } else {
        // Сообщение об ошибке, если не все поля заполнены
        echo "Все поля должны быть заполнены!";
    }
}
?>

