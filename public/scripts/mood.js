document.addEventListener("DOMContentLoaded", async function () {

    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    let currentMood = 'first';
    let activeButton = null;

    emotionButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }
            button.classList.toggle('active');
            activeButton = button.classList.contains('active') ? button : null;
        });
    });

    async function loadFoodMoodData(emotionId) {
        try {
            const response = await fetch(`/getFoodMood.php?emotion_id=${emotionId}`);
            if (response.ok) {
                const data = await response.json();
                return data;
            } else {
                console.error("Ошибка загрузки данных:", response.statusText);
                return [];
            }
        } catch (error) {
            console.error("Ошибка:", error);
            return [];
        }
    }

    function displayFoodMood(foodData) {
        foodListContainer.innerHTML = '';
        foodData.forEach(item => {
            const listItemHTML = `
                <li class="foodList-li">
                    <h3 class="foodList-li-title">${item.title}</h3>
                    <div class="foodList-image">
                        <img src="${item.img}" alt="${item.title} image">
                    </div>
                </li>
            `;

            foodListContainer.innerHTML += listItemHTML;
        });
    }

    document.getElementById('skip-left').addEventListener('click', () => {
        if (currentMood === 'first') {
            currentMood = 'left';
        } else if (currentMood === 'right') {
            currentMood = 'first';
        } else {
            currentMood = 'right';
        }
        displayFoodMood(currentMood);
    });

    document.getElementById('skip-right').addEventListener('click', () => {
        if (currentMood === 'first') {
            currentMood = 'right';
        } else if (currentMood === 'left') {
            currentMood = 'first';
        } else {
            currentMood = 'left';
        }
        displayFoodMood(currentMood);
    });

    displayFoodMood('first');

    document.getElementById('calculateBtn').addEventListener('click', () => {
        currentMood = 'first';
        displayFoodMood(currentMood);
        foodMoodListSection.classList.remove('d-none');
        document.getElementById('emotionCalculator').classList.add('d-none');
    });

    document.getElementById('backToEmotionList').addEventListener('click', () => {
        foodMoodListSection.classList.add('d-none');
        document.getElementById('emotionCalculator').classList.remove('d-none');
    });

    // modal

    const modal = document.getElementById('modal');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnEmotion10 = document.getElementById('emotion10');
    const btnAge = document.getElementById('btnAge');

    btnEmotion10.addEventListener('click', () => {
        modal.classList.remove('modal-none');
        modal.classList.add('modal-show');
    });

    btnCloseModal.addEventListener('click', () => {
        modal.classList.add('modal-none');
        modal.classList.remove('modal-show');
    });

    btnAge.addEventListener('click', () => {
        modal.classList.add('modal-none');
        modal.classList.remove('modal-show');               // переделать
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('modal-none');
            modal.classList.remove('modal-show');
        }
    });
});


document.addEventListener("DOMContentLoaded", async function () {
    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    console.log(emotionButtons);
    let currentMood = 'first';
    let currentMoodIndex = 0;
    let activeButton = null;
    const moods = ['first', 'left', 'right']; // Атрибуты для слайдера с рецептами

    async function loadFoodMoodData(emotionId) {
        try {
            const response = await fetch(`/getFoodMood.php?emotion_id=${emotionId}`);
            if (response.ok) {
                const data = await response.json();
                console.log(data);
                return data;
            } else {
                console.error("Ошибка загрузки данных:", response.statusText);
                return [];
            }
        } catch (error) {
            console.error("Ошибка:", error);
            return [];
        }
    }

    function displayFoodMood(foodData) {
        if (!foodListContainer || !foodData) {
            console.error("Данные для отображения отсутствуют или контейнер не найден");
            return;
        }

        foodListContainer.innerHTML = '';
        foodData.forEach(item => {
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

    emotionButtons.forEach((button, index) => {
        button.addEventListener('click', async function () {
            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }
            button.classList.toggle('active');
            activeButton = button.classList.contains('active') ? button : null;

            currentMood = moods[index];
            currentMoodIndex = index;
            const foodData = await loadFoodMoodData(index + 1); // Передача emotion_id в запрос (index + 1, предположим что эмоции 1, 2, 3)
            displayFoodMood(foodData);
        });
    });

    const skipLeftButton = document.getElementById('skip-left');
    const skipRightButton = document.getElementById('skip-right');

    backToEmotionList.addEventListener('click', () => {
        foodMoodListSection.classList.add('d-none');
        document.getElementById('emotionCalculator').classList.remove('d-none');
    });


});

// document.addEventListener("DOMContentLoaded", async function () {
//     const foodListContainer = document.getElementById('foodList-container');
//     const foodMoodListSection = document.getElementById('foodMoodList');
//     const emotionButtons = document.querySelectorAll('.emotion-item');
//     const calculateBtn = document.getElementById('calculateBtn');
//     const backToEmotionList = document.getElementById('backToEmotionList');
//     let activeButton = null;

//     async function loadFoodMoodData(emotionId) {
//         try {
//             const response = await fetch(`/getFoodMood.php?emotion_id=${emotionId}`);
//             if (response.ok) {
//                 const data = await response.json();
//                 return data;
//             } else {
//                 console.error("Ошибка загрузки данных:", response.statusText);
//                 return [];
//             }
//         } catch (error) {
//             console.error("Ошибка:", error);
//             return [];
//         }
//     }

    // function displayFoodMood(foodData) {
    //     foodListContainer.innerHTML = '';
    //     foodData.forEach(item => {
    //         const listItemHTML = `
    //             <li class="foodList-li">
    //                 <h3 class="foodList-li-title">${item.title}</h3>
    //                 <div class="foodList-image">
    //                     <img src="${item.img}" alt="${item.title} image">
    //                 </div>
    //             </li>
    //         `;
    //         foodListContainer.insertAdjacentHTML('beforeend', listItemHTML);
    //     });
    // }

    // emotionButtons.forEach(button => {
    //     button.addEventListener('click', function () {
    //         if (activeButton && activeButton !== button) {
    //             activeButton.classList.remove('active');
    //         }
    //         button.classList.toggle('active');
    //         activeButton = button.classList.contains('active') ? button : null;
    //     });
    // });

//     calculateBtn.addEventListener('click', async () => {
//         if (activeButton) {
//             const emotionId = activeButton.getAttribute('data-emotian-id');
//             const foodData = await loadFoodMoodData(emotionId);
//             displayFoodMood(foodData);
//             foodMoodListSection.classList.remove('d-none');
//             document.getElementById('emotionCalculator').classList.add('d-none');
//         } else {
//             console.warn("Эмоция не выбрана. Пожалуйста, выберите эмоцию перед расчетом.");
//         }
//     });

//     backToEmotionList.addEventListener('click', () => {
//         foodMoodListSection.classList.add('d-none');
//         document.getElementById('emotionCalculator').classList.remove('d-none');
//     });
// });
