
<?php
session_start();
require_once 'session_hendler.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="../scripts/notifications.js"></script>
    
    <link rel="stylesheet" href="styles/menu.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="images/logo_browser/logo_browser_2.png" type="image/png">
    <link rel="stylesheet" href="styles/scrollBar.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script defer src="scripts/index.js"></script>
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
                            <?php if(isset($_SESSION['username'])): ?>
                                <li><a href="pages/profile.php" class="menu-item">Profile</a></li>
                            <?php else: ?>
                                <li><a href="pages/register.php" class="menu-item">Sign In/Sign Up</a></li>
                            <?php endif; ?>        
                            <li><a href="pages/search.php" class="menu-item">Food Recipes</a></li>
                            <li><a href="pages/mood.php" class="menu-item">Mood Recipes</a></li>
                            <?php if(isset($_SESSION['username'])): ?>
                                <li><a href="pages/chat.php" class="menu-item">Chat</a></li>
                            <?php else: ?>
                            <?php endif; ?>  
                            <li><a href="pages/help.html" class="menu-item">Help</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="start">
            <b>FoodMood</b>                 
            <h1 class="title">Daily Menu Based on Emotions</h1>
            <p>We brighten your emotions by adding a touch of flavor to your day. 
                Check out our menu options and select your favorite, 
                crafted based on your emotions.
            </p>
            <button type="button" class="btnGetStarted">Get started...</button>

            <div id="modal" class="modal custom-modal modal-none">
                <div class="modal-content custom-modal-content">
                    <p>This feature is intended only for registered users.</p>
                    <ul class="modal-buttons">
                        <li><button id="btnCloseModal" class="modal-btn">Close</button></li>
                        <li><button style="text-transform: uppercase;" id="btnLogin" class="modal-btn">Sign up or try</button></li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="promo">
            <h2 class="d-none">Promo Image</h2>
            <img src="images/menu/salad.png" alt="promo image" class="promo-image">
        </section>
    </main>

    <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 footer-section">
                        <h5>About FoodMood</h5>
                        <p>FoodMood is your personal food assistant, helping you explore new 
                            recipes and customize your meal plans according to your 
                            mood. Discover delicious recipes, whether you're 
                            happy, sad, or anything in between! </p> 
                    </div>
                    <div class="col-md-4 footer-section">
                        <h5>Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="index_startPage.html">Main Page</a></li>
                            <li><a href="pages/register.php">Sign In/Sign Up</a></li>
                            <li><a href="pages/search.php">Food Recipes</a></li>
                            <li><a href="pages/mood.php">Mood Recipes</a></li>
                            <li><a href="pages/help.html">Help</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 footer-section">
                        <h5>Contact Us</h5>
                        <p>Email: <a href="mailto:support@FoodMood.com">support@FoodMood.com</a></p>
                        <p>Phone: +420 777 430 106</p>
                        <div class ="footer-social-links">
                            <a href="#"><img src="../images/footer/facebookdefault.svg" alt="Facebook"></a>
                            <a href="#"><img src="../images/footer/instadefault.svg" alt="Instagram"></a>
                            <a href="#"><img src="../images/footer/youtubedefault.svg" alt="YouTube"></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>© 2024 FoodMood. All rights reserved.</p>
                </div>
            </div>
        </footer>

    <script  defer src="scripts/scroll.js"></script>

    <script>
const inactivityLimit = 1200000;
let inactivityTimer; 

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        alert("Вы были неактивны слишком долго. Вас перенаправят на страницу входа.");
        window.location.href = "pages/register.php"; 
    }, inactivityLimit);
}

["mousemove", "keydown", "click", "scroll"].forEach((event) => {
    window.addEventListener(event, resetInactivityTimer);
});

resetInactivityTimer();

  </script>
</body>
</html>