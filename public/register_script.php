<?php
session_start();

// Подключение к базе данных
$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$username = 'wk4kwaf4w8x12twh';
$password = 'ijw8uyd2lwkgf8on';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

// Функция валидации входных данных
function validateInputs($username, $lastname, $email, $phone_number, $login, $password): array
{
    $errors = [];

    if (!preg_match('/^[a-zA-Z]{2,}$/', $username)) {
        $errors[] = "Name must contain only Latin letters and be at least 2 characters long.";
    }
    if (!preg_match('/^[a-zA-Z]{2,}$/', $lastname)) {
        $errors[] = "Last name must contain only Latin letters and be at least 2 characters long.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^\+\d{1,3}\d{9,12}$/', $phone_number)) {
        $errors['phone_number'] = "Phone number must start with a country code (e.g., +1) and have a maximum of 12 digits.";
    }
    

    if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $login)) {
        $errors[] = "Login must contain only Latin letters, numbers, and underscores, and be at least 3 characters long.";
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, and a number.";
    }

    return $errors;
}
$_SESSION['form_errors'] = $errors;

// Сохранение успешного сообщения
$_SESSION['success_message'] = "Registration successful!";

// Проверка уникальности логина и email
function isUserUnique($pdo, $login, $email): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = :login OR email = :email");
    $stmt->execute(['login' => $login, 'email' => $email]);
    return $stmt->fetchColumn() == 0; // Если пользователь не найден, возвращаем true
}

// Регистрация пользователя
function registerUser($pdo, $username, $lastname, $email, $phone_number, $gender, $login, $password, $profile_picture): string
{
    // Проверяем уникальность логина и email
    if (!isUserUnique($pdo, $login, $email)) {
        return "User with this login or email already exists.";
    }

    // Хешируем пароль
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Подготовленный запрос для вставки данных
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
}

// Обработка данных при отправке формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $gender = $_POST['gender'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $profile_picture = null;

    // Если пользователь загрузил изображение
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
    }

    // Проверяем данные
    $errors = validateInputs($username, $lastname, $email, $phone_number, $login, $password);

    if (!empty($errors)) {
        // Выводим ошибки
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Пытаемся зарегистрировать пользователя
        $result = registerUser($pdo, $username, $lastname, $email, $phone_number, $gender, $login, $password, $profile_picture);
        echo "<p style='color: green;'>$result</p>";
    }
}
?>
