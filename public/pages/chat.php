<?php
session_start();
require '../../config.php'; // Подключаем файл с настройками и подключением к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    header('Location: register.php');
    exit();
}

// Получаем имя пользователя и ID текущего пользователя
$username = $_SESSION['username'];
$query = "SELECT id FROM users WHERE LOWER(username) = LOWER(?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $user_id = $user_data['id'];
    $_SESSION['user_id'] = $user_id;
} else {
    die('Пользователь не найден');
}

// Проверяем, нужно ли показывать всех пользователей
$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';

// Если выбрано "Все", получаем всех пользователей
if ($show_all) {
    $query = "SELECT id, username, profile_picture_blob FROM users WHERE id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
} else {
    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
    if (!empty($search_query)) {
        $query = "SELECT id, username, profile_picture_blob FROM users 
                  WHERE id != ? AND username LIKE CONCAT('%', ?, '%')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_id, $search_query);
    } else {
        $query = "SELECT f.favorite_id AS id, u.username, u.profile_picture_blob
                  FROM favorites f
                  JOIN users u ON f.favorite_id = u.id
                  WHERE f.user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    }
}
$stmt->execute();
$users_result = $stmt->get_result();
$users = $users_result->fetch_all(MYSQLI_ASSOC);
$stmt->execute();
$users_result = $stmt->get_result();
$users = $users_result->fetch_all(MYSQLI_ASSOC);

// Получаем ID выбранного собеседника (по умолчанию первый пользователь из списка)
$selected_user_id = isset($_GET['user']) ? (int)$_GET['user'] : ($users[0]['id'] ?? null);

// Получаем сообщения между текущим пользователем и выбранным собеседником
if ($selected_user_id) {
    $query = "SELECT m.message, m.created_at, 
                     CASE WHEN m.sender_id = ? THEN 'You' ELSE u.username END AS sender
              FROM messages m
              JOIN users u ON m.sender_id = u.id
              WHERE (m.sender_id = ? AND m.recipient_id = ?)
                 OR (m.sender_id = ? AND m.recipient_id = ?)
              ORDER BY m.created_at ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiii", $user_id, $user_id, $selected_user_id, $selected_user_id, $user_id);
    $stmt->execute();
    $messages_result = $stmt->get_result();
    $messages = $messages_result->fetch_all(MYSQLI_ASSOC);
} else {
    $messages = [];
}

$query = "SELECT favorite_id FROM favorites WHERE user_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Ошибка подготовки запроса: ' . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$favorites_result = $stmt->get_result();
if (!$favorites_result) {
    die('Ошибка выполнения запроса: ' . $stmt->error);
}
$favorites = $favorites_result->fetch_all(MYSQLI_ASSOC);

// Преобразуем массив избранных для удобства проверки
$favorite_ids = array_column($favorites, 'favorite_id');
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="../styles/chat.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="icon" href="../images/logo_browser/logo_browser_2.png" type="image/png">

    <link rel="stylesheet" href="../styles/menu.css" />
</head>
<body>
<title>FoodMood</title>
  </head>
  <body>
    <header class="header">
        <nav class="navbar">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel">Menu</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="list-unstyled">
                            <li><a href="../index_startPage.php" class="menu-item">Main Page</a></li>
                            <!-- <li><a href="register.html" class="menu-item">Sign In/Sign Up</a></li> -->
                            <li><a href="search.php" class="menu-item">Food Recipes</a></li>
                            <li><a href="mood.html" class="menu-item">Mood Recipes</a></li>
                            <li><a href="help.html" class="menu-item">Help</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

<div class="messenger-container">
    <!-- Список пользователей -->
    <div class="user-list">
        <h3>Users</h3>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Serch users" value="<?= htmlspecialchars($search_query) ?>">
            <button class="serch" type="submit">Serch</button>
            <a href="?show_all=1" class="show-all-btn">All</a>
        </form>
        <ul>
            <?php foreach ($users as $user): ?>
                <li class="<?= $user['id'] === $selected_user_id ? 'active' : '' ?>">
                    <a href="?user=<?= $user['id'] ?>">
                        <span class="user-icon">👤</span> <!-- Иконка пользователя -->
                        <?= htmlspecialchars($user['username']) ?>
                    </a>
                    <form method="POST" action="add_favorite.php" class="add-favorite-form">
                        <input type="hidden" name="favorite_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="favorite-btn">
                        <span class="star <?= in_array($user['id'], array_column($favorites, 'favorite_id')) ? 'filled' : '' ?>">
                            ★
                        </span>
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Чат -->
    <div class="chat-window">
        <div class="chat-header">
            <h3>
                Chat with <?= htmlspecialchars($users[array_search($selected_user_id, array_column($users, 'id'))]['username'] ?? 'select user') ?>
            </h3>
        </div>
        <div class="chat-messages">
            <?php if ($messages): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?= $msg['sender'] === 'You' ? 'outgoing' : 'incoming' ?>">
                        <p><strong><?= htmlspecialchars($msg['sender']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?></p>
                        <span class="timestamp"><?= $msg['created_at'] ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-messages">Chat is empty.</p>
            <?php endif; ?>
        </div>
        <form method="POST" action="send_message.php" class="message-form">
            <input type="hidden" name="recipient_id" value="<?= $selected_user_id ?>">
            <textarea name="message" placeholder="Enter your message" required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</div>
</body>
</html>
