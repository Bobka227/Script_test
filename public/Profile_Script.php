<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit();
}

// Получаем текущий логин пользователя из сессии
$current_login = $_SESSION['login'];

// Подключение к базе данных
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$db_username = 'emk2ggh76qbpq4ml';
$db_password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных']);
    exit();
}

// Функция для проверки, является ли пользователь администратором
function isAdmin($pdo, $login) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && $user['role'] === 'admin';
}

// Проверяем, является ли текущий пользователь администратором
$is_admin = isAdmin($pdo, $current_login);

// Проверяем, какое действие нужно выполнить
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'change_password':
                // Изменение пароля
                $target_login = $is_admin && !empty($_POST['target_login']) ? $_POST['target_login'] : $current_login;
                if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];

                    if ($new_password !== $confirm_password) {
                        echo json_encode(['status' => 'error', 'message' => 'Новые пароли не совпадают']);
                        exit();
                    }

                    // Хешируем новый пароль
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Обновляем пароль в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE login = :login");
                    $stmt->execute(['password' => $hashed_password, 'login' => $target_login]);

                    echo json_encode(['status' => 'success', 'message' => 'Пароль успешно изменен']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля']);
                    exit();
                }
                break;

            case 'change_email':
                // Изменение email
                $target_login = $is_admin && !empty($_POST['target_login']) ? $_POST['target_login'] : $current_login;
                if (!empty($_POST['new_email'])) {
                    $new_email = $_POST['new_email'];

                    // Валидация email
                    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(['status' => 'error', 'message' => 'Неверный формат email']);
                        exit();
                    }

                    // Проверяем, нет ли уже такого email
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND login != :login");
                    $stmt->execute(['email' => $new_email, 'login' => $target_login]);
                    if ($stmt->rowCount() > 0) {
                        echo json_encode(['status' => 'error', 'message' => 'Этот email уже используется']);
                        exit();
                    }

                    // Обновляем email в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE login = :login");
                    $stmt->execute(['email' => $new_email, 'login' => $target_login]);

                    echo json_encode(['status' => 'success', 'message' => 'Email успешно изменен']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, введите новый email']);
                    exit();
                }
                break;

            case 'change_phone':
                // Изменение номера телефона
                $target_login = $is_admin && !empty($_POST['target_login']) ? $_POST['target_login'] : $current_login;
                if (!empty($_POST['new_phone'])) {
                    $new_phone = $_POST['new_phone'];

                    // Валидация номера телефона
                    if (!preg_match('/^\+?[0-9]{7,15}$/', $new_phone)) {
                        echo json_encode(['status' => 'error', 'message' => 'Неверный номер телефона']);
                        exit();
                    }

                    // Обновляем номер телефона в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET phone_number = :phone WHERE login = :login");
                    $stmt->execute(['phone' => $new_phone, 'login' => $target_login]);

                    echo json_encode(['status' => 'success', 'message' => 'Номер телефона успешно изменен']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, введите новый номер телефона']);
                    exit();
                }
                break;

            case 'change_info':
                // Изменение личной информации
                $target_login = $is_admin && !empty($_POST['target_login']) ? $_POST['target_login'] : $current_login;
                if (!empty($_POST['new_username']) && !empty($_POST['new_lastname']) && !empty($_POST['new_gender']) && !empty($_POST['new_login'])) {
                    $new_username = $_POST['new_username'];
                    $new_lastname = $_POST['new_lastname'];
                    $new_gender = $_POST['new_gender'];
                    $new_login = $_POST['new_login'];

                    // Проверяем, нет ли уже такого логина
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login");
                    $stmt->execute(['login' => $new_login]);
                    if ($new_login !== $target_login && $stmt->rowCount() > 0) {
                        echo json_encode(['status' => 'error', 'message' => 'Этот логин уже используется']);
                        exit();
                    }

                    // Обновляем информацию в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET username = :username, lastname = :lastname, gender = :gender, login = :new_login WHERE login = :target_login");
                    $stmt->execute([
                        'username' => $new_username,
                        'lastname' => $new_lastname,
                        'gender' => $new_gender,
                        'new_login' => $new_login,
                        'target_login' => $target_login
                    ]);

                    // Обновляем логин в сессии, если текущий пользователь изменяет свои данные
                    if ($target_login === $current_login) {
                        $_SESSION['login'] = $new_login;
                        $_SESSION['username'] = $new_username;
                    }

                    echo json_encode(['status' => 'success', 'message' => 'Информация успешно обновлена']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля']);
                    exit();
                }
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Недопустимое действие']);
                exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Действие не указано']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    exit();
}
?>
