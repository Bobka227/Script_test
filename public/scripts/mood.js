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
    let currentMood = 'first';
    let currentMoodIndex = 0; // Индекс текущего состояния
    let activeButton = null;
    let foodMood = {};  // Это теперь будет объект со всеми эмоциями

    const moods = [];

    // Функция для загрузки данных из PHP-скрипта
    async function loadFoodMoodData() {
        try {
            const response = await fetch('getFoodMood.php');
            if (response.ok) {
                foodMood = await response.json();
                console.log(foodMood); // Проверяем, что данные загружены

                // Создаем массив всех ключей из объекта foodMood
                Object.keys(foodMood).forEach(key => moods.push(key));
            } else {
                console.error("Ошибка загрузки данных:", response.statusText);
            }
        } catch (error) {
            console.error("Ошибка:", error);
        }
    }

    // Функция для отображения рецептов в зависимости от настроения
    function displayFoodMood(page) {
        if (!foodListContainer) {
            console.error("foodListContainer не найдено");
            return;
        }

        foodListContainer.innerHTML = '';

        foodMood[page].forEach(item => {
            const listItemHTML = `
                <li class="foodList-li">
                    <h3 class="foodList-li-title">${item.title}</h3>
                    <div class="foodList-image">
                        <img src="${item.img}" alt="${item.title} image">
                    </div>
                </li>
            `;
            foodListContainer.insertAdjacentHTML('beforeend', listItemHTML);
        });
    }

    if (emotionButtons.length > 0) {
        // Обработчики для кнопок эмоций
        emotionButtons.forEach((button, index) => {
            button.addEventListener('click', function () {
                if (activeButton && activeButton !== button) {
                    activeButton.classList.remove('active');
                }
                button.classList.toggle('active');
                activeButton = button.classList.contains('active') ? button : null;

                currentMood = moods[index];
                displayFoodMood(currentMood);
            });
        });
    } else {
        console.error("Элементов с классом 'emotion-item' не найдено");
    }

    const skipLeftButton = document.getElementById('skip-left');
    const skipRightButton = document.getElementById('skip-right');
    const calculateBtn = document.getElementById('calculateBtn');
    const backToEmotionList = document.getElementById('backToEmotionList');

    if (skipLeftButton) {
        skipLeftButton.addEventListener('click', () => {
            currentMoodIndex = (currentMoodIndex - 1 + moods.length) % moods.length;
            currentMood = moods[currentMoodIndex];
            displayFoodMood(currentMood);
        });
    } else {
        console.error("skip-left элемент не найден");
    }

    if (skipRightButton) {
        skipRightButton.addEventListener('click', () => {
            currentMoodIndex = (currentMoodIndex + 1) % moods.length;
            currentMood = moods[currentMoodIndex];
            displayFoodMood(currentMood);
        });
    } else {
        console.error("skip-right элемент не найден");
    }

    if (calculateBtn) {
        calculateBtn.addEventListener('click', () => {
            currentMood = 'first';
            currentMoodIndex = moods.indexOf(currentMood);
            displayFoodMood(currentMood);
            if (foodMoodListSection) {
                foodMoodListSection.classList.remove('d-none');
            } else {
                console.error("foodMoodListSection элемент не найден");
            }
            const emotionCalculator = document.getElementById('emotionCalculator');
            if (emotionCalculator) {
                emotionCalculator.classList.add('d-none');
            } else {
                console.error("emotionCalculator элемент не найден");
            }
        });
    } else {
        console.error("calculateBtn элемент не найден");
    }

    if (backToEmotionList) {
        backToEmotionList.addEventListener('click', () => {
            if (foodMoodListSection) {
                foodMoodListSection.classList.add('d-none');
            } else {
                console.error("foodMoodListSection элемент не найден");
            }
            const emotionCalculator = document.getElementById('emotionCalculator');
            if (emotionCalculator) {
                emotionCalculator.classList.remove('d-none');
            } else {
                console.error("emotionCalculator элемент не найден");
            }
        });
    } else {
        console.error("backToEmotionList элемент не найден");
    }

    // Загрузка данных при загрузке страницы
    loadFoodMoodData();
});