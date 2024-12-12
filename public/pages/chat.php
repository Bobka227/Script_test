<?php
global $conn;
session_start();
require '../../config.php'; // Подключаем файл с настройками и подключением

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php'); // Перенаправление, если пользователь не авторизован
    exit();
}

$user_id = $_SESSION['user_id']; // Получаем ID пользователя из сессии

// Получаем список пользователей для выпадающего списка
$query = "SELECT id, username FROM users";
$result = $conn->query($query);

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC); // Получаем всех пользователей в виде ассоциативного массива
} else {
    die('Ошибка выполнения запроса: ' . $conn->error);
}

// Получаем список сообщений для чата
$query = "SELECT id, sender_id, recipient_id, message, created_at FROM messages WHERE sender_id = ? OR recipient_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id); // связываем параметры
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die('Ошибка выполнения запроса: ' . $conn->error);
}
?>

<h2>Чат</h2>

<form method="POST" action="send_message.php">
    <select name="recipient_id">
        <?php foreach ($users as $user) { ?>
            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
        <?php } ?>
    </select>
    <textarea name="message" placeholder="Введите сообщение" required></textarea>
    <button type="submit">Отправить</button>
</form>

<h3>Сообщения</h3>
<?php
// Получаем все сообщения для текущего пользователя
$query = "SELECT m.message, m.created_at, u.username FROM messages m
          JOIN users u ON m.sender_id = u.id
          WHERE m.sender_id = ? OR m.recipient_id = ?
          ORDER BY m.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($msg = $result->fetch_assoc()) {
        echo "<p><strong>{$msg['username']}</strong>: {$msg['message']} ({$msg['created_at']})</p>";
    }
} else {
    echo 'Ошибка при загрузке сообщений: ' . $conn->error;
}
?>
