<?php
session_start();
if (isset($_SESSION['login'])) {
    // Если пользователь уже авторизован, перенаправляем его на профиль
    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta charset="utf-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/menu.css">
    <link rel="icon" href="../images/logo_browser/logo_browser_2.png" type="image/png">

    <link rel="stylesheet" type="text/css" href="../styles/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">

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

    <main class="main-main">
      <div class="main">
        <div class="container a-container" id="a-container">
          <form class="form" id="a-form" action="../register_script.php" method="POST" enctype="multipart/form-data">
            <h2 class="form_title title">Create Account</h2>
            <input class="form__input" type="text" name="username" placeholder="Name" required>
            <input class="form__input" type="text" name="lastname" placeholder="Last Name" required>
            <input class="form__input" type="email" name="email" placeholder="Email" required
              pattern=".+@.+"
              oninvalid="this.setCustomValidity('Please include an @ in the email address.')"
              oninput="this.setCustomValidity('')">
            <input class="form__input" type="text" name="phone_number" placeholder="Phone number" required>
            <select class="form__input" name="gender" required>
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
            <input class="form__input" type="text" name="login" placeholder="Login" required>
            <input class="form__input" type="password" name="password" placeholder="Password" required>
            <label for="profile_picture">Upload Profile Picture</label>
            <div class="file-upload">
              <input type="file" name="profile_picture" accept="image/*" class="img-choose" id="profile_picture">
              <label for="profile_picture" class="file-upload-label">Choose your image</label>
            </div>
            <button class="form__button button submit" type="submit">SIGN UP</button>
          </form>
        </div>
        <div class="container b-container" id="b-container">
          <form class="form" id="b-form" action="../login.php" method="POST">
            <h2 class="form_title title">Sign in to Website</h2>
            <input class="form__input" type="text" name="login" placeholder="Email or Login" required>
            <input class="form__input" type="password" name="password" placeholder="Password" required>
            <button class="form__button button submit" type="submit">SIGN IN</button>
          </form>
        </div>
        <div class="switch" id="switch-cnt">
          <div class="switch__circle"></div>
          <div class="switch__circle switch__circle--t"></div>
          <div class="switch__container" id="switch-c1">
            <h2 class="switch__title title">Welcome Back!</h2>
            <p class="switch__description description">To keep connected with us please login with your personal info</p>
            <button class="switch__button button switch-btn">SIGN IN</button>
          </div>
          <div class="switch__container is-hidden" id="switch-c2">
            <h2 class="switch__title title">Hello Friend!</h2>
            <p class="switch__description description">Enter your personal details and start your journey with us</p>
            <button class="switch__button button switch-btn">SIGN UP</button>
          </div>
        </div>
      </div>
    </main>
    <script defer src="../scripts/register.js"></script>
  </body>
</html>
