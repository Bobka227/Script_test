document.addEventListener("DOMContentLoaded", async function () {

    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    let currentMood = 'first';
    let currentMoodIndex = 0;
    let activeButton = null;
    const moods = ['first', 'left', 'right'];

    async function loadFoodMoodData(emotionId) {
        try {
            const response = await fetch(`/getFoodMood.php?emotion_id=${emotionId}`);
            if (response.ok) {
                const data = await response.json();
                return data; // Массив рецептов
            } else {
                console.error('Ошибка загрузки данных:', response.statusText);
                return [];
            }
        } catch (error) {
            console.error('Ошибка:', error);
            return [];
        }
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
            const foodData = await loadFoodMoodData(index + 1);
            displayFoodMood(foodData);
        });
    });

    async function displayFoodMood(currentMoodData) {
        const foodListContainer = document.getElementById('foodList-container');
        foodListContainer.innerHTML = '';
    
        if (!currentMoodData || currentMoodData.length === 0) {
            foodListContainer.innerHTML = '<p>Нет данных для отображения.</p>';
            return;
        }
    
        currentMoodData.forEach(item => {
            const title = item.title || 'Без названия';
            const img = item.img || 'default-image-path.png'; // Укажите путь к изображению по умолчанию
            const time = item.time || 'Не указано';
    
            const listItemHTML = `
                <li class="foodList-li">
                    <h3 class="foodList-li-title">${title}</h3>
                    <div class="foodList-image">
                        <img src="${img}" alt="${title} image">
                    </div>
                    <p>Время приема пищи: ${time}</p>
                </li>
            `;
            foodListContainer.innerHTML += listItemHTML;
        });
    }
    

    const skipLeftButton = document.getElementById('skip-left');
    const skipRightButton = document.getElementById('skip-right');

    if (skipLeftButton) {
        skipLeftButton.addEventListener('click', async () => {
            currentMoodIndex = (currentMoodIndex - 1 + moods.length) % moods.length;
            currentMood = moods[currentMoodIndex];
            const foodData = await loadFoodMoodData(currentMoodIndex + 1);
            displayFoodMood(foodData);
        });
    }

    if (skipRightButton) {
        skipRightButton.addEventListener('click', async () => {
            currentMoodIndex = (currentMoodIndex + 1) % moods.length;
            currentMood = moods[currentMoodIndex];
            const foodData = await loadFoodMoodData(currentMoodIndex + 1);
            displayFoodMood(foodData);
        });
    }

    const initialFoodData = await loadFoodMoodData(currentMoodIndex + 1);
    displayFoodMood(initialFoodData);

    const calculateBtn = document.getElementById('calculateBtn');
    const backToEmotionList = document.getElementById('backToEmotionList');

    if (calculateBtn) {
        calculateBtn.addEventListener('click', async () => {
            if (!activeButton) {
                showCalculateModal();
                return;
            }
            currentMood = 'first';
            currentMoodIndex = moods.indexOf(currentMood);
            const foodData = await loadFoodMoodData(currentMoodIndex + 1);
            displayFoodMood(foodData);
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

    // modal

    const modal = document.getElementById('modal');
    const modalCalculate = document.getElementById('modal-calculate');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnEmotion10 = document.getElementById('emotion10');
    const modalCalculateBtn = document.getElementById('btnCloseModalCalculate');
    const btnAge = document.getElementById('btnAge');

    function showCalculateModal() {
        modalCalculate.classList.remove('modal-none');
        modalCalculate.classList.add('modal-show');
    }

    btnEmotion10.addEventListener('click', () => {
        modal.classList.remove('modal-none');
        modal.classList.add('modal-show');
    });

    btnCloseModal.addEventListener('click', () => {
        modal.classList.add('modal-none');
        modal.classList.remove('modal-show');
    });

    modalCalculateBtn.addEventListener('click', () => {
        modalCalculate.classList.remove('modal-show');
        modalCalculate.classList.add('modal-none');
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
