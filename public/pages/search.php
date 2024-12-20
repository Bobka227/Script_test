
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
    <link rel="stylesheet" href="../styles/search.css">
    <link rel="stylesheet" href="../styles/scrollBar.css" />
    <link rel="stylesheet" href="../styles/notification.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="../images/logo_browser/logo_browser_2.png" type="image/png">

    <script defer src="../scripts/search.js"></script>
    <title>FoodMood</title>
</head>
<body>
<script src="../scripts/notifications.js"></script>
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
    
    <main>
        <section class="search-recipe">
            <h1 class="d-none">Search for recipes</h1>
            <input type="text" id="search-bar" class="search-bar" placeholder="Search for recipes...">
        </section>
        <section class="filtres-list">
            <div class="filtres-container">
                <h2 class="d-none">Search filters</h2>
                <div class="filtre-container">
                    <button class="filter-btn" data-category="vegan">Vegan</button>
                    <button class="filter-btn" data-category="vegetarian">Vegetarian</button>
                </div>
                <div class="filtre-container">
                    <button class="filter-btn" data-category="spicy">Spicy</button>
                    <button class="filter-btn" data-category="quick">Quick</button>
                    <button class="filter-btn" data-category="no oven">No Oven</button>
                </div>
                <div class="filtre-container">
                    <button class="filter-btn" data-category="sweet">Sweet</button>
                    <button class="filter-btn" data-category="grilled">Grilled</button>
                    <button class="filter-btn" data-category="soups">Soups</button>
                    <button class="filter-btn" data-category="drinks">Drinks</button>
                </div>
                <div class="filtre-container">
                    <button class="filter-btn" data-category="Italian">Italian</button>
                    <button class="filter-btn" data-category="Czech">Czech</button>
                    <button class="filter-btn" data-category="Kazakh">Kazakh</button>
                    <button class="filter-btn" data-category="Ukrainian">Ukrainian</button>
                    <button class="filter-btn" data-category="Asian">Asian</button>
                </div>
            </div>
        </section> 
        
        </section>
        <section class="recipes d-none">
            <h5 class="d-none">List of recipes</h5>
            <ul class="recipes-list"></ul>
        </section>
        <section class="news-block" id="newsBlock">
            <h3 class="d-none">Delicious news</h3>
            <article class="news-article">
                <h4 class="d-none">News section</h4>
                <div class="news-info">
                    <b class="news-title">Title</b>
                    <p class="news-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                        Quam quibusdam magnam atque fuga doloribus amet distinctio, eaque laborum accusantium 
                        aut repudiandae optio nesciunt modi, 
                        dignissimos quaerat explicabo architecto nostrum minus!
                    </p> 
                    <br class="swag">
                    <a href="https://www.foodandwine.com/birria-bombs-tiktok-8729361">Read more</a> 
                </div>                  
                <img src="../images/news/cat1.jpeg" alt="news image"> 
            </article>
            <article class="news-article">
                <h4 class="d-none">News section</h4>
                <div class="news-info">
                    <b class="news-title">Title</b>
                    <p class="news-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                        Quam quibusdam magnam atque fuga doloribus amet distinctio, eaque laborum accusantium 
                        aut repudiandae optio nesciunt modi, 
                        dignissimos quaerat explicabo architecto nostrum minus!
                    </p> 
                    <br class="swag">
                    <a href="https://www.foodandwine.com/vegetable-storage-containers-bacterial-growth-8725806">Read more</a> 
                </div>                  
                <img src="../images/news/cat2.jpeg" alt="news image">
            </article>
            <article class="news-article">
                <h4 class="d-none">News section</h4>
                <div class="news-info">
                    <b class="news-title">Title</b>
                    <p class="news-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                        Quam quibusdam magnam atque fuga doloribus amet distinctio, eaque laborum accusantium 
                        aut repudiandae optio nesciunt modi, 
                        dignissimos quaerat explicabo architecto nostrum minus!
                    </p> 
                    <br class="swag">
                    <a href="https://www.foodandwine.com/aldi-thanksgiving-basket-2024-8728597" class="news-link">Read more</a> 
                </div>                  
                <img src="../images/news/cat3.jpg" alt="news image">
            </article>
        </section>

        <section class="search-history" id="searchHistorySection">
            <h5 class="h2 title-history">History</h5>
            <div class="history-block">
                <button id="prevBtn" class="slide-history"><img src="../images/mood/left-skip.png" alt="sipka previous"></button>
                <ul id="history-list" class="history-list"></ul>
                <button id="nextBtn" class="slide-history"><img src="../images/mood/right-skip.png" alt="sipka next"></button>
            </div>
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

    <script src="../scripts/scroll.js"></script>


    <script>
const inactivityLimit = 1200000   ;
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