<?php
session_start();
require_once '../session_hendler.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/menu.css">
    <link rel="stylesheet" href="../styles/notification.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/emoce.css">
    <link rel="icon" href="../images/logo_browser/logo_browser_2.png" type="image/png">
    <script defer src="../scripts/mood.js"></script>
    <title>FoodMood</title>
</head>
<body>
<script src="../scripts/notifications.js"></script>
<header class="header">
    <nav class="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel">Menu</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="list-unstyled">
                    <li><a href="../index_startPage.php" class="menu-item">Main Page</a></li>
                            <?php if(isset($_SESSION['username'])): ?>
                                <li><a href="profile.php" class="menu-item">Profile</a></li>
                            <?php else: ?>
                                <li><a href="register.php" class="menu-item">Sign In/Sign Up</a></li>
                            <?php endif; ?>  
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
    
    <main class="main">
        <section class="emotion-calculator" id="emotionCalculator">
            <h1 class="d-none">Emotion Calculator</h1>
            <ul class="emotions-list" id="emotionsList">
                <li><button class="emotion-item" id="emotion1" data-emotian-id="1" type="button">
                        <img src="../images/mood/emoji/emoji9.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion2" data-emotian-id="2" type="button">
                        <img src="../images/mood/emoji/emoji7.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion3" data-emotian-id="3" type="button">
                        <img src="../images/mood/emoji/emoji3.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion4" data-emotian-id="4" type="button">
                        <img src="../images/mood/emoji/emoji4.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion5" data-emotian-id="5" type="button">
                        <img src="../images/mood/emoji/emoji5.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion6" data-emotian-id="6" type="button">
                        <img src="../images/mood/emoji/emoji6.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion7" data-emotian-id="7" type="button">
                        <img src="../images/mood/emoji/emoji2.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion8" data-emotian-id="8" type="button">
                        <img src="../images/mood/emoji/emoji8.png" alt="#">
                    </button></li>
                <li><button class="emotion-item" id="emotion9" data-emotian-id="9" type="button">
                        <img src="../images/mood/emoji/emoji1.png" alt="#">
                    </button></li>
                <li><button class="emotion-item emotion-ten" id="emotion10" type="button">
                        <img src="../images/mood/emoji/emoji10.png" alt="#">
                    </button></li>
            </ul>
    
            <button class="calculate-btn" id="calculateBtn">Calculate</button>
    
            <div id="modal" class="modal custom-modal modal-none">
                <div class="modal-content custom-modal-content">
                    <p>This feature is intended only for 18+ users.</p>
                    <ul class="modal-buttons">
                        <li><button id="btnCloseModal" class="modal-btn">Close</button></li>
                        <li><button id="btnAge" class="modal-btn">18+</button></li>
                    </ul>
                </div>
            </div>
    
            <div id="modal-calculate" class="modal custom-modal modal-none">
                <div class="modal-content custom-modal-content">
                    <p>Vyberte emoce.</p>
                    <ul class="modal-buttons modal-calculate">
                        <li><button id="btnCloseModalCalculate" class="modal-btn">OK</button></li>
                    </ul>
                </div>
            </div>
        </section>
    
        <section class="food-mood-list d-none" id="foodMoodList">
            <ul class="foodList-ul" id="foodList-container"></ul>
    
            <div class="food-list-skip">
                <button class="skip skip-left btn" id="skip-left">
                    <img src="../images/mood/left-skip.png" alt="#">
                </button>
                <button class="skip skip-right btn" id="skip-right">
                    <img src="../images/mood/right-skip.png" alt="#">
                </button>
            </div>
    
            <button class="back-to-emotion-list" id="backToEmotionList">Back</button>
        </section>
    
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h5>About FoodMood</h5>
                    <p>FoodMood is your personal food assistant, helping you explore new recipes and customize your meal plans according to your mood. Discover delicious recipes, whether you're happy, sad, or anything in between!</p>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="../index_startPage.html">Main Page</a></li>
                        <li><a href="register.php">Sign In/Sign Up</a></li>
                        <li><a href="search.php">Food Recipes</a></li>
                        <li><a href="mood.php">Mood Recipes</a></li>
                        <li><a href="help.html">Help</a></li>
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

    <script>
    // Время бездействия до завершения сессии (20 минут)
const inactivityLimit = 1200000   ; // 20 минут = 1200000 мс
let inactivityTimer; // Таймер для отслеживания бездействия

// Сброс таймера бездействия
function resetInactivityTimer() {
    clearTimeout(inactivityTimer); // Сбрасываем предыдущий таймер
    inactivityTimer = setTimeout(() => {
        alert("Вы были неактивны слишком долго. Вас перенаправят на страницу входа.");
        window.location.href = "register.php"; 
    }, inactivityLimit);
}

// Отслеживание активности пользователя
["mousemove", "keydown", "click", "scroll"].forEach((event) => {
    window.addEventListener(event, resetInactivityTimer);
});

// Инициализация таймера при загрузке страницы
resetInactivityTimer();

  </script>
</body>
</html>
