<?php
session_start();


if (!isset($_SESSION['username'])) {
    // Если нет, возвращаем ошибку
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
    // Отладочный вывод удалён
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных']);
    exit();
}

// Проверяем, какое действие нужно выполнить
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'change_password':
                // Изменение пароля
                if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
                    $current_password = $_POST['current_password'];
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];

                    if ($new_password !== $confirm_password) {
                        echo json_encode(['status' => 'error', 'message' => 'Новые пароли не совпадают']);
                        exit();
                    }

                    // Получаем текущий хеш пароля из базы данных
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE login = :login");
                    $stmt->execute(['login' => $current_login]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user && password_verify($current_password, $user['password'])) {
                        // Хешируем новый пароль
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                        // Обновляем пароль в базе данных
                        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE login = :login");
                        $stmt->execute(['password' => $hashed_password, 'login' => $current_login]);

                        echo json_encode(['status' => 'success', 'message' => 'Пароль успешно изменен']);
                        exit();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Текущий пароль неверен']);
                        exit();
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля']);
                    exit();
                }
                break;

            case 'change_email':
                // Изменение email
                if (!empty($_POST['new_email'])) {
                    $new_email = $_POST['new_email'];

                    // Валидация email
                    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(['status' => 'error', 'message' => 'Неверный формат email']);
                        exit();
                    }

                    // Проверяем, нет ли уже такого email
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND login != :login");
                    $stmt->execute(['email' => $new_email, 'login' => $current_login]);
                    if ($stmt->rowCount() > 0) {
                        echo json_encode(['status' => 'error', 'message' => 'Этот email уже используется']);
                        exit();
                    }

                    // Обновляем email в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE login = :login");
                    $stmt->execute(['email' => $new_email, 'login' => $current_login]);

                    echo json_encode(['status' => 'success', 'message' => 'Email успешно изменен']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, введите новый email']);
                    exit();
                }
                break;

            case 'change_phone':
                // Изменение номера телефона
                if (!empty($_POST['new_phone'])) {
                    $new_phone = $_POST['new_phone'];

                    // Валидация номера телефона
                    if (!preg_match('/^\+?[0-9]{7,15}$/', $new_phone)) {
                        echo json_encode(['status' => 'error', 'message' => 'Неверный номер телефона']);
                        exit();
                    }

                    // Обновляем номер телефона в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET phone_number = :phone WHERE login = :login");
                    $stmt->execute(['phone' => $new_phone, 'login' => $current_login]);

                    echo json_encode(['status' => 'success', 'message' => 'Номер телефона успешно изменен']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, введите новый номер телефона']);
                    exit();
                }
                break;

            case 'change_info':
                // Изменение личной информации (имя, фамилия, пол, логин)
                if (!empty($_POST['new_username']) && !empty($_POST['new_lastname']) && !empty($_POST['new_gender']) && !empty($_POST['new_login'])) {
                    $new_username = $_POST['new_username'];
                    $new_lastname = $_POST['new_lastname'];
                    $new_gender = $_POST['new_gender'];
                    $new_login = $_POST['new_login'];

                    // Проверяем, нет ли уже такого логина
                    if ($new_login !== $current_login) {
                        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login");
                        $stmt->execute(['login' => $new_login]);
                        if ($stmt->rowCount() > 0) {
                            echo json_encode(['status' => 'error', 'message' => 'Этот логин уже используется']);
                            exit();
                        }
                    }

                    // Обновляем информацию в базе данных
                    $stmt = $pdo->prepare("UPDATE users SET username = :username, lastname = :lastname, gender = :gender, login = :new_login WHERE login = :current_login");
                    $stmt->execute([
                        'username' => $new_username,
                        'lastname' => $new_lastname,
                        'gender' => $new_gender,
                        'new_login' => $new_login,
                        'current_login' => $current_login
                    ]);

                    // Обновляем логин в сессии
                    $_SESSION['login'] = $new_login;
                    $_SESSION['username'] = $new_username;

                    echo json_encode(['status' => 'success', 'message' => 'Информация успешно обновлена']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля']);
                    exit();
                }
                break;

           case 'change_avatar':
    // Изменение аватара
    if (!empty($_FILES['avatar']['name'])) {
        $avatar = $_FILES['avatar'];

        // Проверяем наличие ошибок при загрузке файла
        if ($avatar['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки файла']);
            exit();
        }

        // Проверяем тип файла
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($avatar['type'], $allowed_types)) {
            echo json_encode(['status' => 'error', 'message' => 'Неверный тип файла. Разрешены только JPG, PNG и GIF']);
            exit();
        }

        // Получаем содержимое файла
        $avatarData = file_get_contents($avatar['tmp_name']);
        $avatarType = $avatar['type'];

        // Обновляем данные в базе данных
        $stmt = $pdo->prepare("UPDATE users SET profile_picture_blob = :avatarData, profile_picture_type = :avatarType WHERE login = :login");
        $stmt->bindParam(':avatarData', $avatarData, PDO::PARAM_LOB);
        $stmt->bindParam(':avatarType', $avatarType);
        $stmt->bindParam(':login', $current_login);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Аватар успешно обновлен']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, выберите файл']);
        exit();
    }
    break;


            default:
                echo json_encode(['status' => 'error', 'message' => 'Недопустимое действие']);
                exit();
                break;
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
