document.addEventListener("DOMContentLoaded", function () {
    
    // display with menu
    
    const foodMood = {
        first: [
            { title: 'Breakfast', img: 'breakfast1.avif' },
            { title: 'Lunch', img: 'lunch1.jpg' },
            { title: 'Dinner', img: 'dinner1.jpg' }
        ],
        left: [
            { title: 'Breakfast', img: 'breakfast2.jpg' },
            { title: 'Lunch', img: 'lunch2.jpg' },
            { title: 'Dinner', img: 'dinner2.jpg' }
        ],
        right: [
            { title: 'Breakfast', img: 'breakfast3.jpg' },
            { title: 'Lunch', img: 'lunch3.jpg' },
            { title: 'Dinner', img: 'dinner3.jpg' }
        ]
    };
    
    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    const emotionButtons = document.querySelectorAll('.emotion-item');
    let currentMood = 'first';
    let activeButton = null;

    emotionButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }

            if (button.classList.contains('active')) {
                button.classList.remove('active');
                activeButton = null;
            } else {
                button.classList.add('active');
                activeButton = button;
            }
        });
    });
    
    function displayFoodMood(page) {
        foodListContainer.innerHTML = '';
    
        foodMood[page].forEach(item => {
            const listItemHTML = `
            <li class="foodList-li">
                <h3 class="foodList-li-title">${item.title}</h3>
                <div class="foodList-image">
                    <img src="../images/mood/${item.img}" alt="${item.title} image">
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