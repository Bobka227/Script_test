<?php
session_start(); // Инициализация сессии

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    // Если нет, перенаправляем на страницу входа
    header("Location: register.php");
    exit();
}

// Получаем имя пользователя из сессии
$username = $_SESSION['username'];
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
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получаем путь к аватарке из базы данных
$stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && !empty($user['profile_picture'])) {
    $avatarPath = '../' . $user['profile_picture'];
} else {
    $avatarPath = '../images/default_avatar.png'; // Путь к аватарке по умолчанию
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" href="../images/logo_browser/logo_browser_2.png" type="image/png">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/menu.css" />
  <link rel="stylesheet" href="../styles/profile.css" />

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
              <li><a href="search.html" class="menu-item">Food Recipes</a></li>
              <li><a href="mood.html" class="menu-item">Mood Recipes</a></li>
              <li><a href="help.html" class="menu-item">Help</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <div class="profile-container">
    <div class="avatar-container">
       <div class="profile-pic">
       <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="avatar" class="ava-img" id="avatarImage" data-bs-toggle="modal" data-bs-target="#avatarModal">
       </div>
      <div style="font-size: 50px;" class="profile-name" id="profile-name" ><?php echo htmlspecialchars($username); ?></div>
    </div>

    <div class="buttons-changer">
      <button class="profile-option">CHANGE PASSWORD</button>
      <button class="profile-option">CHANGE EMAIL</button>
      <button class="profile-option">CHANGE PHONE NUMBER</button>
      <button class="profile-option">CHANGE OWN INFORMATION</button> 
    </div>

    <!-- Кнопка выхода -->
    <button class="btn logout-button" id="logout-button" data-bs-toggle="modal" data-bs-target="#logoutModal">LOG OUT</button>

    </div>
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to log out?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmLogout" class="btn btn-danger">Log Out</button>
      </div>
    </div>
  </div>
</div>

<!-- Модальное окно для загрузки аватарки -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="avatarForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="avatarModalLabel">Upload Avatar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="file" name="avatar" id="avatarInput" accept="image/*" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function() {
  // Handle the confirm logout button click
  $('#confirmLogout').on('click', function() {
    // Proceed with logout
    $.ajax({
      url: '../logout.php',
      method: 'POST',
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          window.location.href = 'register.php';
        } else {
          alert('Error logging out');
        }
      },
      error: function() {
        alert('Error logging out');
      }
    });
  });
  $('#avatarForm').on('submit', function(e) {
    e.preventDefault(); // Предотвращаем стандартное действие формы

    var formData = new FormData(this);

    $.ajax({
      url: '../upload_avatar.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json', // Ожидаем JSON-ответ
      success: function(response) {
        if (response.status === 'success') {
          // Обновляем аватарку на странице
          $('#avatarImage').attr('src', response.new_avatar_path + '?' + new Date().getTime());
          // Закрываем модальное окно
          var avatarModal = bootstrap.Modal.getInstance(document.getElementById('avatarModal'));
          avatarModal.hide();
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('An error occurred while uploading the avatar: ' + textStatus);
      }
    });
  });
});
  </script>
</body>
</html>
