document.addEventListener("DOMContentLoaded", async function () {
    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    const calculateBtn = document.getElementById('calculateBtn');
    const backToEmotionList = document.getElementById('backToEmotionList');
    const nextVariantBtn = document.getElementById('skip-right');
    const prevVariantBtn = document.getElementById('skip-left');
    let activeButton = null;
    let currentVariantIndex = 0;
    let allVariants = [];

    async function loadFoodMoodData(emotionId) {
        try {
            const response = await fetch(`/arr_recipes.php?emotion_id=${emotionId}`);
            if (response.ok) {
                const data = await response.json();
                console.log("Загруженные данные:", data);
                return data;
            } else {
                console.error("Ошибка загрузки данных:", response.status, response.statusText);
                return [];
            }
        } catch (error) {
            console.error("Ошибка:", error);
            return [];
        }
    }

    function displayFoodMood(foodData) {
        foodListContainer.innerHTML = '';
        const currentVariant = foodData[currentVariantIndex];
        if (!currentVariant) return;
    
        const categories = ['breakfast', 'lunch', 'dinner'];
        categories.forEach(category => {
            const meal = currentVariant[category];
            if (meal) {
                const listItemHTML = `
                    <li class="foodList-li">
                        <h3 class="food-category">${category.toUpperCase()}</h3>
                        <div class="foodList-image">
                            <img src="${meal.image}" alt="${meal.name}">
                        </div>
                    </li>
                `;
                foodListContainer.insertAdjacentHTML('beforeend', listItemHTML);
            }
        });
    }
    

    emotionButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }
            button.classList.toggle('active');
            activeButton = button.classList.contains('active') ? button : null;
        });
    });

    calculateBtn.addEventListener('click', async () => {
        if (activeButton) {
            const emotionId = activeButton.getAttribute('data-emotian-id');
            allVariants = await loadFoodMoodData(emotionId);
            if (allVariants.length === 0) {
                console.warn("Нет доступных данных для выбранной эмоции.");
                return;
            }
            currentVariantIndex = 0;
            displayFoodMood(allVariants);
            foodMoodListSection.classList.remove('d-none');
            document.getElementById('emotionCalculator').classList.add('d-none');
        } else {
            console.warn("Эмоция не выбрана. Пожалуйста, выберите эмоцию перед расчетом.");
        }
    });

    nextVariantBtn.addEventListener('click', () => {
        if (currentVariantIndex < allVariants.length - 1) {
            currentVariantIndex++;
            displayFoodMood(allVariants);
        }
    });

    prevVariantBtn.addEventListener('click', () => {
        if (currentVariantIndex > 0) {
            currentVariantIndex--;
            displayFoodMood(allVariants);
        }
    });

    backToEmotionList.addEventListener('click', () => {
        foodMoodListSection.classList.add('d-none');
        document.getElementById('emotionCalculator').classList.remove('d-none');
    });

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
