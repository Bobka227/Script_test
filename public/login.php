<?php

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Подключение к базе данных успешно!<br>"; // Отладка
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

function loginUser($pdo, $email, $password): string
{
    echo "Функция входа вызвана<br>"; // Отладка

    // Проверка на существующего пользователя по email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() === 0) {
        echo "Пользователь не найден<br>"; // Отладка
        return "Вход не успешен.";
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка пароля
    if (password_verify($password, $user['password'])) {
        echo "Пользователь успешно аутентифицирован<br>"; // Отладка
        return "Вход успешен!";
    } else {
        echo "Неправильный пароль<br>"; // Отладка
        return "Вход не успешен.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST запрос получен<br>"; // Отладка

    // Проверка, что все необходимые поля не пустые
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Вызов функции входа
        $result = loginUser($pdo, $email, $password);
        echo $result;
    } else {
        echo "Все поля должны быть заполнены!";
    }
}


