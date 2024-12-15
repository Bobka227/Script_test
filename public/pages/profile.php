<?php
session_start(); 
require_once '../session_hendler.php';

if (!isset($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}

$current_login = $_SESSION['username'];

$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$db_username = 'emk2ggh76qbpq4ml';
$db_password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

function isAdmin($pdo, $login) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE username = :username");
    $stmt->execute(['username' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && $user['role'] === 'admin';
}

$is_admin = isAdmin($pdo, $current_login);

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $current_login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && !empty($user['profile_picture'])) {
    $avatarPath = '../' . $user['profile_picture'];
} else {
    $avatarPath = '../images/default_avatar.png'; 
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" href="../images/logo_browser/logo_browser_2.png" type="image/png">

  <link rel="stylesheet" href="../styles/menu.css" />
  <link rel="stylesheet" href="../styles/profile.css" />
  <link rel="stylesheet" href="../styles/scrollBar.css" />
    <link rel="stylesheet" href="../styles/notification.css">

  <style>
    .profile-pic img {
      border-radius: 0; /* Убираем округление */
    }
  </style>

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
              <li><a href="search.php" class="menu-item">Food Recipes</a></li>
              <li><a href="mood.php" class="menu-item">Mood Recipes</a></li>
              <?php if(isset($_SESSION['username'])): ?>
                     <li><a href="chat.php" class="menu-item">Chat</a></li>
              <?php else: ?>
              <?php endif; ?> 
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
        <img src="display_avatar.php" alt="avatar" class="ava-img" id="avatarImage" data-bs-toggle="modal" data-bs-target="#avatarModal">
      </div>
      <div style="font-size: 50px;" class="profile-name" id="profile-name"><?php echo htmlspecialchars($user['username'] . ' ' . $user['lastname']); ?></div>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userInfoModal">View User Info</button>
    </div>
  
    
    <div class="buttons-changer">
      <button class="profile-option change-password">CHANGE PASSWORD</button>
      <button class="profile-option change-email">CHANGE EMAIL</button>
      <button class="profile-option change-phone">CHANGE PHONE NUMBER</button>
      <button class="profile-option change-info">CHANGE PERSONAL INFORMATION</button>
      <?php if ($is_admin): ?>
        <button class="profile-option view-all-users" id="viewAllUsersButton">VIEW ALL USERS</button>
      <?php endif; ?>
    </div>

    <!-- Кнопка выхода -->
    <button class="btn logout-button" id="logout-button" data-bs-toggle="modal" data-bs-target="#logoutModal">LOG OUT</button>
  </div>

  <!-- Модальное окно для подтверждения выхода -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Logout</h5>
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

  <!-- Модальное окно для изменения пароля -->
  <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="changePasswordForm">
          <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="password" name="current_password" placeholder="Current Password" required class="form-control mb-2">
            <input type="password" name="new_password" placeholder="New Password" required class="form-control mb-2">
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Change Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Модальное окно для изменения email -->
  <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="changeEmailForm">
          <div class="modal-header">
            <h5 class="modal-title">Change Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="email" name="new_email" placeholder="New Email" required class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Change Email</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Модальное окно для изменения номера телефона -->
  <div class="modal fade" id="changePhoneModal" tabindex="-1" aria-labelledby="changePhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="changePhoneForm">
          <div class="modal-header">
            <h5 class="modal-title">Change Phone Number</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="text" name="new_phone" placeholder="New Phone Number" required class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Change Phone Number</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Модальное окно для изменения личной информации -->
  <div class="modal fade" id="changeInfoModal" tabindex="-1" aria-labelledby="changeInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="changeInfoForm">
          <div class="modal-header">
            <h5 class="modal-title">Change Personal Information</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="text" name="new_username" placeholder="New First Name" required class="form-control mb-2">
            <input type="text" name="new_lastname" placeholder="New Last Name" required class="form-control mb-2">
            <select name="new_gender" required class="form-control mb-2">
              <option value="">Select Gender</option>
              <option value="male" <?php if ($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
              <option value="female" <?php if ($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
            </select>
            <input type="text" name="new_login" placeholder="New Login" required class="form-control" value="<?php echo htmlspecialchars($user['login']); ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Change Information</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Модальное окно для загрузки аватара -->
  <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="avatarForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Avatar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" required class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadButton">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Обработчик отправки формы загрузки аватара
    document.getElementById('avatarForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Останавливаем стандартное поведение формы

        const formData = new FormData(this);

        fetch('upload_image.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Обновляем аватар и перезагружаем страницу
                document.getElementById('avatarImage').src = 'display_avatar.php?' + new Date().getTime();
                setTimeout(() => {
                    location.reload(); // Обновление страницы через 500мс
                }, 500);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Ошибка загрузки аватара: ' + error.message);
        });
    });
</script>

<img src="display_avatar.php" alt="User Avatar" class="ava-img" id="avatarImage" style="display:none">

<!-- Модальное окно с информацией о пользователе -->
<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Lastname:</strong> <?php echo htmlspecialchars($user['lastname']); ?></p>
        <p><strong>Login:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  <!-- Подключение скриптов -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../scripts/profile.js"></script>
  <script src="../scripts/scroll.js"></script>





  <script>
const inactivityLimit = 1200000  ; 
let inactivityTimer; 

function resetInactivityTimer() {
    clearTimeout(inactivityTimer); 
    inactivityTimer = setTimeout(() => {
        alert("Вы были неактивны слишком долго. Вас перенаправят на страницу входа.");
        window.location.href = "register.php"; 
    }, inactivityLimit);
}

["mousemove", "keydown", "click", "scroll"].forEach((event) => {
    window.addEventListener(event, resetInactivityTimer);
});

resetInactivityTimer();

  </script>
</body>
</html>
