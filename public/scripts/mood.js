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


document.addEventListener("DOMContentLoaded", async function () {
    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    const moods = ['first', 'sad', 'happy']; // Define available moods here
    let currentMood = 'first';
    let currentMoodIndex = 0;
    let activeButton = null;
    const itemsPerPage = 3;

    // Load food mood data based on the selected mood
    async function loadFoodMoodData(mood = 'first') {
        if (!moods.includes(mood)) {
            console.error("Invalid mood:", mood);
            return;
        }
        try {
            const response = await fetch(`/../getFoodMood.php?emotion=${mood}`);
            if (response.ok) {
                const recipes = await response.json();
                displayFoodMood(recipes, mood, 0);
            } else {
                console.error("Data loading error:", response.statusText);
            }
        } catch (error) {
            console.error("Fetch error:", error);
        }
    }

    // Display the paginated list of recipes based on mood
    function displayFoodMood(recipes, mood, pageIndex) {
        foodListContainer.innerHTML = '';

        const start = pageIndex * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedRecipes = recipes.slice(start, end);

        if (!paginatedRecipes.length) {
            const noDataMessage = document.createElement("p");
            noDataMessage.textContent = "No recipes available for this mood.";
            foodListContainer.appendChild(noDataMessage);
            return;
        }

        paginatedRecipes.forEach(recipe => {
            const recipeElement = document.createElement("div");
            recipeElement.className = "recipe";
            recipeElement.innerHTML = `
                <h3>${recipe.title}</h3>
                <img src="${recipe.img}" alt="${recipe.title}">
            `;
            foodListContainer.appendChild(recipeElement);
        });

        updatePaginationControls(recipes, pageIndex);
    }

    // Set up pagination controls
    function updatePaginationControls(recipes, pageIndex) {
        const totalPages = Math.ceil(recipes.length / itemsPerPage);
        const skipLeftButton = document.getElementById('skip-left');
        const skipRightButton = document.getElementById('skip-right');

        skipLeftButton.onclick = () => {
            const newPageIndex = (pageIndex - 1 + totalPages) % totalPages;
            displayFoodMood(recipes, currentMood, newPageIndex);
        };

        skipRightButton.onclick = () => {
            const newPageIndex = (pageIndex + 1) % totalPages;
            displayFoodMood(recipes, currentMood, newPageIndex);
        };
    }

    // Set up mood buttons to load data on selection
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

    // Set up page skip buttons for navigating moods
    document.getElementById('skip-left')?.addEventListener('click', () => {
        currentMoodIndex = (currentMoodIndex - 1 + moods.length) % moods.length;
        currentMood = moods[currentMoodIndex];
        loadFoodMoodData(currentMood);
    });

    document.getElementById('skip-right')?.addEventListener('click', () => {
        currentMoodIndex = (currentMoodIndex + 1) % moods.length;
        currentMood = moods[currentMoodIndex];
        loadFoodMoodData(currentMood);
    });

    // Calculate button logic
    document.getElementById('calculateBtn')?.addEventListener('click', () => {
        loadFoodMoodData(currentMood);
        foodMoodListSection.classList.remove('d-none');
        document.getElementById('emotionCalculator')?.classList.add('d-none');
    });

    // Back button logic
    document.getElementById('backToEmotionList')?.addEventListener('click', () => {
        foodMoodListSection.classList.add('d-none');
        document.getElementById('emotionCalculator')?.classList.remove('d-none');
    });

    // Initial load
    await loadFoodMoodData(currentMood);
});
