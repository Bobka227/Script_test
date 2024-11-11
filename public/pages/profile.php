 
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
      <div class="profile-pic"><img src="#" alt="avatar" class="ava-img"></div>
      <div class="profile-name" id="profile-name"><?php echo htmlspecialchars($username); ?></div>
    </div>

    <div class="buttons-changer">
      <button class="profile-option">CHANGE PASSWORD</button>
      <button class="profile-option">CHANGE EMAIL</button>
      <button class="profile-option">CHANGE PHONE NUMBER</button>
      <button class="profile-option">CHANGE OWN INFORMATION</button> 
    </div>

    <!-- Кнопка выхода -->
    <button class="btn logout-button"><a href="../logout.php"></a>LOG OUT</button>
    </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function() {
      // Загрузка имени пользователя
      $.ajax({
        url: 'get_login.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          $('#profile-name').text(response.login);
        },
        error: function() {
          $('#profile-name').text('Error loading user data');
        }
      });

      // Обработчик клика для выхода из аккаунта
      $('#logout-button').on('click', function() {
        $.ajax({
          url: '../logout.php',
          method: 'GET',
          success: function() {
            window.location.href = 'register.php'; // Перенаправление на страницу входа
          },
          error: function() {
            alert('Error logging out');
          }
        });
      });
    });
  </script>
</body>
</html>
