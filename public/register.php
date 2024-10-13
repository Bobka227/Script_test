<?php

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'jb5d0wgfic7sbeq4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Подключение к базе данных успешно!<br>"; // Отладка
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}


function registerUser($pdo, $username, $email, $password) {
    echo "Функция регистрации вызвана<br>"; // Отладка

    // Проверка на существующего пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "Пользователь уже существует<br>"; // Отладка
        return "Пользователь с таким именем или email уже существует.";
    }

    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    echo "Пароль захеширован<br>"; // Отладка

    // Вставка нового пользователя
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

    try {
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ]);
        echo "Данные успешно добавлены в базу данных<br>"; // Отладка
    } catch (PDOException $e) {
        echo "Ошибка вставки данных: " . $e->getMessage() . "<br>"; // Отладка
        return "Ошибка при регистрации.";
    }

    return "Регистрация успешна!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST запрос получен<br>"; // Отладка
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($pdo, $username, $email, $password);
    echo $result;
}
?>
