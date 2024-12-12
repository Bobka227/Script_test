<?php
session_start(); // Инициализация сессии

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}

// Получаем логин текущего пользователя из сессии
$current_login = $_SESSION['username'];

// Подключение к базе данных
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$db_username = 'emk2ggh76qbpq4ml';
$db_password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Проверяем, является ли пользователь администратором
function isAdmin($pdo, $login) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE username = :username");
    $stmt->execute(['username' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && $user['role'] === 'admin';
}

if (!isAdmin($pdo, $current_login)) {
    die("Доступ запрещён. Вы не администратор.");
}

// Обработка обновления данных пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];

        // Обновление данных пользователя в базе данных
        $stmt = $pdo->prepare("UPDATE users SET username = :username, lastname = :lastname, email = :email, phone_number = :phone_number, gender = :gender, role = :role WHERE id = :id");
        $stmt->execute([
            'username' => $username,
            'lastname' => $lastname,
            'email' => $email,
            'phone_number' => $phone_number,
            'gender' => $gender,
            'role' => $role,
            'id' => $user_id
        ]);

        echo "<p>User data with ID $user_id has been successfully updated.</p>";
    }
}

// Получение всех пользователей из базы данных
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View All Users</title>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">All Users</h1>

    <!-- Кнопка возврата на страницу профиля -->
    <a href="profile.php" class="btn btn-primary mb-4">Back to Profile</a>

    <!-- Таблица с пользователями -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <form method="post" action="view_all_users.php">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control"></td>
                    <td><input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" class="form-control"></td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control"></td>
                    <td><input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" class="form-control"></td>
                    <td>
                        <select name="gender" class="form-control">
                            <option value="male" <?php if ($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
                            <option value="female" <?php if ($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
                        </select>
                    </td>
                    <td>
                        <select name="role" class="form-control">
                            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" name="update_user" class="btn btn-success">Update</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
