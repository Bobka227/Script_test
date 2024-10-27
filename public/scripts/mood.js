// document.addEventListener("DOMContentLoaded", function () {
//
//     // display with menu
//
//     const foodMood = {
//         first: [
//             { title: 'Breakfast', img: 'breakfast1.avif' },
//             { title: 'Lunch', img: 'lunch1.jpg' },
//             { title: 'Dinner', img: 'dinner1.jpg' }
//         ],
//         left: [
//             { title: 'Breakfast', img: 'breakfast2.jpg' },
//             { title: 'Lunch', img: 'lunch2.jpg' },
//             { title: 'Dinner', img: 'dinner2.jpg' }
//         ],
//         right: [
//             { title: 'Breakfast', img: 'breakfast3.jpg' },
//             { title: 'Lunch', img: 'lunch3.jpg' },
//             { title: 'Dinner', img: 'dinner3.jpg' }
//         ]
//     };
//
//     const foodListContainer = document.getElementById('foodList-container');
//     const foodMoodListSection = document.getElementById('foodMoodList');
//     const emotionButtons = document.querySelectorAll('.emotion-item');
//     let currentMood = 'first';
//     let activeButton = null;
//
//     emotionButtons.forEach(button => {
//         button.addEventListener('click', function() {
//             if (activeButton && activeButton !== button) {
//                 activeButton.classList.remove('active');
//             }
//
//             if (button.classList.contains('active')) {
//                 button.classList.remove('active');
//                 activeButton = null;
//             } else {
//                 button.classList.add('active');
//                 activeButton = button;
//             }
//         });
//     });
//
//     function displayFoodMood(page) {
//         foodListContainer.innerHTML = '';
//
//         foodMood[page].forEach(item => {
//             const listItemHTML = `
//             <li class="foodList-li">
//                 <h3 class="foodList-li-title">${item.title}</h3>
//                 <div class="foodList-image">
//                     <img src="../images/mood/${item.img}" alt="${item.title} image">
//                 </div>
//             </li>
//         `;
//
//             foodListContainer.innerHTML += listItemHTML;
//         });
//     }
//
//     document.getElementById('skip-left').addEventListener('click', () => {
//         if (currentMood === 'first') {
//             currentMood = 'left';
//         } else if (currentMood === 'right') {
//             currentMood = 'first';
//         } else {
//             currentMood = 'right';
//         }
//         displayFoodMood(currentMood);
//     });
//
//     document.getElementById('skip-right').addEventListener('click', () => {
//         if (currentMood === 'first') {
//             currentMood = 'right';
//         } else if (currentMood === 'left') {
//             currentMood = 'first';
//         } else {
//             currentMood = 'left';
//         }
//         displayFoodMood(currentMood);
//     });
//
//     displayFoodMood('first');
//
//     document.getElementById('calculateBtn').addEventListener('click', () => {
//         currentMood = 'first';
//         displayFoodMood(currentMood);
//         foodMoodListSection.classList.remove('d-none');
//         document.getElementById('emotionCalculator').classList.add('d-none');
//     });
//
//     document.getElementById('backToEmotionList').addEventListener('click', () => {
//         foodMoodListSection.classList.add('d-none');
//         document.getElementById('emotionCalculator').classList.remove('d-none');
//     });
//
//     // modal
//
//     const modal = document.getElementById('modal');
//     const btnCloseModal = document.getElementById('btnCloseModal');
//     const btnEmotion10 = document.getElementById('emotion10');
//     const btnAge = document.getElementById('btnAge');
//
//     btnEmotion10.addEventListener('click', () => {
//         modal.classList.remove('modal-none');
//         modal.classList.add('modal-show');
//     });
//
//     btnCloseModal.addEventListener('click', () => {
//         modal.classList.add('modal-none');
//         modal.classList.remove('modal-show');
//     });
//
//     btnAge.addEventListener('click', () => {
//         modal.classList.add('modal-none');
//         modal.classList.remove('modal-show');               // переделать
//     });
//
//     window.addEventListener('click', (e) => {
//         if (e.target === modal) {
//             modal.classList.add('modal-none');
//             modal.classList.remove('modal-show');
//         }
//     });
// });


document.addEventListener("DOMContentLoaded", async function fetchFoodMoodData() {
    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    let currentMood = 'first';
    let currentMoodIndex = 0;
    let activeButton = null;
    const moods = [];
    const itemsPerPage = 3;

    async function loadFoodMoodData(mood = 'first') {
        try {
            const response = await fetch(`/../getFoodMood.php?emotion=${mood}`);
            console.log("Запрашиваемое настроение:", mood);

            if (response.ok) {
                const recipes = await response.json();
                console.log("Полученные рецепты:", recipes);

                if (recipes.error) {
                    console.error("Ошибка:", recipes.error);
                } else {
                    displayFoodMood(recipes, mood, 0);
                }
            } else {
                console.error("Ошибка загрузки данных:", response.statusText);
            }
        } catch (error) {
            console.error("Ошибка:", error);
        }
    }

    function displayFoodMood(recipes, mood, pageIndex) {
        const recipesContainer = document.getElementById("foodList-container");
        recipesContainer.innerHTML = '';

        // Пагинация: показываем только `itemsPerPage` рецептов
        const start = pageIndex * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedRecipes = recipes.slice(start, end);

        if (!paginatedRecipes || paginatedRecipes.length === 0) {
            const noDataMessage = document.createElement("p");
            noDataMessage.textContent = "No recipes available for this mood.";
            recipesContainer.appendChild(noDataMessage);
            return;
        }

        paginatedRecipes.forEach(recipe => {
            const recipeElement = document.createElement("div");
            recipeElement.className = "recipe";
            recipeElement.innerHTML = `
            <h3>${recipe.title}</h3>
            <img src="${recipe.img}" alt="${recipe.title}">
        `;
            recipesContainer.appendChild(recipeElement);
        });

        updatePaginationControls(recipes, pageIndex);
    }


    function updatePaginationControls(recipes, pageIndex) {
        const totalItems = recipes.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const skipLeftButton = document.getElementById('skip-left');
        const skipRightButton = document.getElementById('skip-right');

        if (!skipLeftButton || !skipRightButton) return;

        // Навигация с цикличностью
        skipLeftButton.onclick = () => {
            const newPageIndex = (pageIndex - 1 + totalPages) % totalPages; // Переход к последней странице с первой
            displayFoodMood(recipes, currentMood, newPageIndex);
        };

        skipRightButton.onclick = () => {
            const newPageIndex = (pageIndex + 1) % totalPages; // Переход к первой странице с последней
            displayFoodMood(recipes, currentMood, newPageIndex);
        };
    }

    if (emotionButtons) {
        emotionButtons.forEach((button, index) => {
            button.addEventListener('click', function () {
                if (activeButton && activeButton !== button) {
                    activeButton.classList.remove('active');
                }
                button.classList.toggle('active');
                activeButton = button.classList.contains('active') ? button : null;
                currentMood = button.dataset.mood;
                currentMoodIndex = index;
                loadFoodMoodData(currentMood);
            });
        });
    }

    const skipLeftButton = document.getElementById('skip-left');
    const skipRightButton = document.getElementById('skip-right');

    if (skipLeftButton) {
        skipLeftButton.addEventListener('click', () => {
            currentMoodIndex = (currentMoodIndex - 1 + moods.length) % moods.length;
            currentMood = moods[currentMoodIndex];
            loadFoodMoodData(currentMood);
        });
    }

    if (skipRightButton) {
        skipRightButton.addEventListener('click', () => {
            currentMoodIndex = (currentMoodIndex + 1) % moods.length;
            currentMood = moods[currentMoodIndex];
            loadFoodMoodData(currentMood);
        });
    }

    const calculateBtn = document.getElementById('calculateBtn');
    const backToEmotionList = document.getElementById('backToEmotionList');

    if (calculateBtn) {
        calculateBtn.addEventListener('click', () => {
            currentMood = 'first';
            currentMoodIndex = moods.indexOf(currentMood);
            loadFoodMoodData(currentMood);
            foodMoodListSection?.classList.remove('d-none');
            document.getElementById('emotionCalculator')?.classList.add('d-none');
        });
    }

    if (backToEmotionList) {
        backToEmotionList.addEventListener('click', () => {
            foodMoodListSection?.classList.add('d-none');
            document.getElementById('emotionCalculator')?.classList.remove('d-none');
        });
    }

    // Загрузка данных при загрузке страницы
    await loadFoodMoodData();
});