
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles/menu.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" href="images/logo_browser/logo_browser_2.png" type="image/png">


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
                            <li><a href="pages/search.html" class="menu-item">Food Recipes</a></li>
                            <li><a href="pages/mood.html" class="menu-item">Mood Recipes</a></li>
                            <li><a href="pages/TestHtml.php" class="menu-item">Help</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="start">
            <b>Salmon Bass</b>
            <h1 class="title">salmon bass mackerel trout</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Debitis corporis id, laboriosam quod 
                veniam dicta sed quaerat voluptas dolores 
                numquam minima, odio sunt. Molestiae, libero autemporno.
            </p>
            <button type="button" class="btnGetStarted">Get started...</button>

            <div id="modal" class="modal custom-modal modal-none">
                <div class="modal-content custom-modal-content">
                    <p>This feature is intended only for registered users.</p>
                    <ul class="modal-buttons">
                        <li><button id="btnCloseModal" class="modal-btn">Close</button></li>
                        <li><button id="btnLogin" class="modal-btn">Sign in or Sign up</button></li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="promo">
            <h2 class="d-none">Promo Image</h2>
            <img src="images/menu/salad.png" alt="promo image" class="promo-image">
        </section>
    </main>
    <footer></footer>
</body>
</html>