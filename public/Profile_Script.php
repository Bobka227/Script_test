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

// Проверяем, какое действие нужно выполнить
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'change_password':
                // Изменение пароля
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
                    $stmt->execute(['password' => $hashed_password, 'login' => $current_login]);

                    echo json_encode(['status' => 'success', 'message' => 'Пароль успешно изменен']);
                    exit();
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
                        echo json_encode(['status' => 'error', 'message' => 'Недопустимый тип файла. Разрешены только JPG, PNG и GIF']);
                        exit();
                    }

                    // Проверяем размер файла
                    $maxFileSize = 2 * 1024 * 1024; // 2 MB
                    if ($avatar['size'] > $maxFileSize) {
                        echo json_encode(['status' => 'error', 'message' => 'Размер файла превышает 2 MB']);
                        exit();
                    }

                    // Читаем содержимое файла
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
                    echo json_encode(['status' => 'error', 'message' => 'Файл не был загружен']);
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
