<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/menu.css">
    <script defer src="/scripts/mood.js"></script>
    <script defer src="/scripts/menu.js"></script>
    <title>JidloSmidlo</title>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <div class="burger-menu">
                <button class="burgerBtn" id="btnBurgerMenu">
                    <img src="/images/menu/burger.png" alt="burger icon">
                </button>
                <button class="crossBtn d-none" id="crossBurgerMenu">
                    <img src="/images/menu/cross.svg" alt="cross icon">
                </button>

                <ul class="menu d-none">
                    <li><a href="registration.html" class="menu-item">sign in</a></li>
                    <li><a href="registration.html" class="menu-item">sign up</a></li>
                    <li><a href="search.html" class="menu-item">food recipes</a></li>
                    <li><a href="mood.html" class="menu-item">mood recipes</a></li>
                    <li><a href="help.html" class="menu-item">help</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="emotionCalculator" id="emotionCalculator">
            <h1 class="d-none">Emotion calculator</h1>
            <ul class="emotionsList" id="emotionsList">
                <li>
                    <button class="emotion-item" id="emotion1" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion2" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion3" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion4" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion5" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion6" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion7" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion8" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li>
                    <button class="emotion-item" id="emotion9" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
                <li class="emotion">
                    <button class="emotion-item" id="emotion10" type="button"> emoce
                        <!-- смайлик эмоции -->
                    </button>
                </li>
            </ul>

            <button class="calculateBtn" id="calculateBtn">calculate</button>

            <div id="modal" class="modal d-none">
                <div class="modal-content">
                    <p>This feature is intended only for 18+ users.</p>
                    <ul class="modal-buttons">
                        <li><button id="btnCloseModal" class="modal-btn">Close</button></li>
                        <li><button id="btnNoRUnction" class="modal-btn d-none">Wow</button></li>
                        <li><button id="btnAge" class="modal-btn">18+</button></li>
                    </ul>
                </div>
            </div>            
        </section>

        <section class="foodMoodList d-none" id="foodMoodList">
            <h2 class="d-none">The menu for the day according to the emotion</h2>
            <ul class="foodList-ul" id="foodList-container"> <!-- вставка html-кода в js --> </ul>

            <div class="foodListSkip">
                <button class="skip skip-left" id="skip-left">
                    <!-- изображение стрелка --> skip left
                </button>
                <button class="skip skip-right" id="skip-right">
                    <!-- изображение стрелка --> skip right
                </button>
            </div>

            <button class="backToEmotionList" id="backToEmotionList">
                <!-- изображение стрелка --> back
            </button>
        </section>
    </main>
    <footer></footer>
</body>
</html>