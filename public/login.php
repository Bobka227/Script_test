<?php
session_start();
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Подключение к базе данных успешно!<br>"; // Можно удалить отладочный вывод
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

function loginUser($pdo, $login, $password): string
{
    // Проверка на существующего пользователя по email, имени пользователя или логину
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :login OR username = :login OR login = :login");
    $stmt->execute(['login' => $login]);

    if ($stmt->rowCount() === 0) {
        return "Вход не успешен.";
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка пароля
    if (password_verify($password, $user['password'])) {
        // Установка сессии пользователя
        $_SESSION['username'] = $user['username'];
        $_SESSION['login'] = $user['login']; // Добавлено

        // Перенаправление на профиль
        header("Location: /profile.php"); // Измените путь, если необходимо
        exit(); // Важно завершить скрипт после перенаправления
    } else {
        return "Вход не успешен.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка, что все необходимые поля не пустые
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Вызов функции входа
        $result = loginUser($pdo, $login, $password);

        if ($result === "Вход не успешен.") {
            echo $result;
        }
        // Если вход успешен, перенаправление уже произошло в функции loginUser
    } else {
        echo "Все поля должны быть заполнены!";
    }
}
?>
