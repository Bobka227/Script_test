<?php
session_start();

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

function registerUser($pdo, $username, $lastname, $email, $phone_number, $gender, $login, $password, $profile_picture): string
{
    echo "Функция регистрации вызвана<br>"; // Отладка

    // Проверка на существующего пользователя по login или email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login OR email = :email");
    $stmt->execute(['login' => $login, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "Пользователь уже существует<br>"; // Отладка
        return "Пользователь с таким логином или email уже существует.";
    }

    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    echo "Пароль захеширован<br>"; // Отладка

    // Вставка нового пользователя
    $stmt = $pdo->prepare("INSERT INTO users (username, lastname, email, phone_number, gender, login, password, profile_picture) 
                           VALUES (:username, :lastname, :email, :phone_number, :gender, :login, :password, :profile_picture)");

    try {
        $stmt->execute([
            'username' => $username,
            'lastname' => $lastname,
            'email' => $email,
            'phone_number' => $phone_number,
            'gender' => $gender,
            'login' => $login,
            'password' => $hashed_password,
            'profile_picture' => $profile_picture
        ]);
        echo "Данные успешно добавлены в базу данных<br>"; // Отладка

        // Установка сессии для нового пользователя
        $_SESSION['user_id'] = $pdo->lastInsertId(); // ID нового пользователя
        $_SESSION['username'] = $username;

        // Перенаправление на страницу профиля
        header("Location: pages/profile.php");

        exit();
    } catch (PDOException $e) {
        echo "Ошибка вставки данных: " . $e->getMessage() . "<br>"; // Отладка
        return "Ошибка при регистрации.";
    }

    return "Регистрация успешна!";
} 

function validateInputs($username, $lastname, $email, $phone_number, $login, $password): array
{
    $errors = [];

    // Проверка имени и фамилии (только латиница)
    if (!preg_match("/^[a-zA-Z]+$/", $username)) {
        $errors[] = "The name must contain only Latin letters.";
    }
    if (!preg_match("/^[a-zA-Z]+$/", $lastname)) {
        $errors[] = "The last name must contain only Latin letters.";
    }

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Проверка номера телефона (только цифры, минимум 10 символов)
    if (!preg_match("/^\d{10,15}$/", $phone_number)) {
        $errors[] = "Phone number must contain only digits and be at least 10 characters long.";
    }

    // Проверка логина (только латиница, цифры и подчеркивания)
    if (!preg_match("/^[a-zA-Z0-9_]{3,}$/", $login)) {
        $errors[] = "The login must be at least 3 characters long and contain only Latin letters, numbers, or underscores.";
    }

    // Проверка пароля (минимум 8 символов, хотя бы одна цифра, буква верхнего и нижнего регистра)
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $errors[] = "Пароль должен быть не менее 8 символов, содержать хотя бы одну цифру, одну букву верхнего и одну букву нижнего регистра.";
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST запрос получен<br>"; // Отладка

    // Проверка, что все необходимые поля не пустые
    if (!empty($_POST['username']) && !empty($_POST['lastname']) && !empty($_POST['email']) &&
        !empty($_POST['phone_number']) && !empty($_POST['gender']) && !empty($_POST['login']) && !empty($_POST['password'])) {

        $username = $_POST['username'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        
        // Обработка изображения профиля (если загружено)
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
        } else {
            $profile_picture = null; // Если нет изображения, значение будет null
        }

        // Вызов функции регистрации
        $result = registerUser($pdo, $username, $lastname, $email, $phone_number, $gender, $login, $password, $profile_picture);
        echo $result;
    } else {
        echo "Все поля должны быть заполнены!";
    }
}
?>
