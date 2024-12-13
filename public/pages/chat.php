<?php
session_start();
require '../../config.php'; // Подключение к базе данных

if (!isset($_SESSION['username'])) {
    header('Location: register.php');
    exit();
}

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

$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

function getUsers($conn, $user_id, $show_all, $search_query) {
    if ($show_all) {
        $query = "SELECT id, username, profile_picture_blob FROM users WHERE id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    } elseif (!empty($search_query)) {
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
    if (!$stmt->execute()) {
        die('Ошибка выполнения запроса: ' . $stmt->error);
    }
    $users_result = $stmt->get_result();
    return $users_result->fetch_all(MYSQLI_ASSOC);
}

$users = getUsers($conn, $user_id, $show_all, $search_query);
$selected_user_id = isset($_GET['user']) ? (int)$_GET['user'] : ($users[0]['id'] ?? null);

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
    if (!$stmt->execute()) {
        die('Ошибка выполнения запроса: ' . $stmt->error);
    }
    $messages_result = $stmt->get_result();
    $messages = $messages_result->fetch_all(MYSQLI_ASSOC);
} else {
    $messages = [];
}

$query = "SELECT favorite_id FROM favorites WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die('Ошибка выполнения запроса: ' . $stmt->error);
}
$favorites_result = $stmt->get_result();
$favorites = $favorites_result->fetch_all(MYSQLI_ASSOC);
$favorite_ids = !empty($favorites) ? array_column($favorites, 'favorite_id') : [];

$selected_user_id = isset($_GET['user']) ? (int)$_GET['user'] : null;

// Получаем информацию о выбранном собеседнике
$selected_user = null;
if ($selected_user_id) {
    $query = "SELECT id, username FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selected_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $selected_user = $result->fetch_assoc();
    }
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="../styles/chat.css">
</head>
<body>
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

    <div id="notifications" class="notifications">
        <p>No new messages</p>
    </div>


    <!-- Чат -->
    <div class="chat-window">
        <div class="chat-header">
            <h3>
                Chat with <?= htmlspecialchars($selected_user['username'] ?? 'select user') ?>
            </h3>

        </div>
        <div class="chat-messages" id="chat-messages">
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
        <form id="message-form" class="message-form">
            <input type="hidden" name="recipient_id" value="<?= $selected_user_id ?>">
            <textarea name="message" placeholder="Enter your message" required></textarea>
            <button type="submit">Send</button>
            <script>
                const messageForm = document.getElementById('message-form');

                messageForm.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Отключаем стандартное поведение формы

                    const formData = new FormData(messageForm);

                    try {
                        const response = await fetch('send_message.php', {
                            method: 'POST',
                            body: formData
                        });

                        if (!response.ok) {
                            console.error('Ошибка отправки сообщения');
                            return;
                        }

                        // Очищаем поле ввода
                        messageForm.querySelector('textarea').value = '';

                        // Обновляем сообщения
                        fetchMessages();
                    } catch (error) {
                        console.error('Ошибка при отправке сообщения:', error);
                    }
                });
            </script>
        </form>
    </div>
</div>

<script>
    const chatMessages = document.getElementById('chat-messages');
    const recipientId = <?= json_encode($selected_user_id) ?>;

    // Функция для получения сообщений
    async function fetchMessages() {
        try {
            const response = await fetch(`get_messages.php?recipient_id=${recipientId}`);
            if (!response.ok) {
                console.error('Ошибка при загрузке сообщений');
                return;
            }
            const messages = await response.json();

            // Очищаем контейнер сообщений
            chatMessages.innerHTML = '';

            // Добавляем сообщения в контейнер
            messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', msg.sender === 'You' ? 'outgoing' : 'incoming');
                messageDiv.innerHTML = `
                    <p><strong>${msg.sender}:</strong> ${msg.message}</p>
                    <span class="timestamp">${msg.created_at}</span>
                `;
                chatMessages.appendChild(messageDiv);
            });

            // Прокрутка вниз
            chatMessages.scrollTop = chatMessages.scrollHeight;
        } catch (error) {
            console.error('Ошибка при запросе сообщений:', error);
        }
    }

    // Автоматическое обновление каждые 2 секунды
    setInterval(fetchMessages, 2000);

    // Подгружаем сообщения при загрузке страницы
    fetchMessages();

        async function checkNewMessages() {
        try {
        const response = await fetch('/check_new_messages.php');
        if (!response.ok) {
        console.error('Ошибка при проверке новых сообщений');
        return;
    }

        const data = await response.json();
        const notifications = document.getElementById('notifications');

        if (data.new_messages > 0) {
        notifications.innerHTML = `<p>You have ${data.new_messages} new message(s)</p>`;
        notifications.classList.add('show');
        setTimeout(() => {
        notifications.classList.remove('show');
    }, 4000); // Убираем уведомление через 4 секунды
    }
    } catch (error) {
        console.error('Ошибка при запросе новых сообщений:', error);
    }
    }

        // Проверяем каждые 10 секунд
        setInterval(checkNewMessages, 10000);

        // Первоначальная проверка
        checkNewMessages();
</script>
</body>
</html>
