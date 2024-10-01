document.addEventListener("DOMContentLoaded", function () {
    const foodMood = {
        first: [
            { title: 'Breakfast', img: 'images/breakfast1.jpg' },
            { title: 'Lunch', img: 'images/lunch1.jpg' },
            { title: 'Dinner', img: 'images/dinner1.jpg' }
        ],
        left: [
            { title: 'Breakfast1', img: 'images/breakfast2.jpg' },
            { title: 'Lunch', img: 'images/lunch2.jpg' },
            { title: 'Dinner', img: 'images/dinner2.jpg' }
        ],
        right: [
            { title: 'Breakfast2', img: 'images/breakfast3.jpg' },
            { title: 'Lunch', img: 'images/lunch3.jpg' },
            { title: 'Dinner', img: 'images/dinner3.jpg' }
        ]
    };
    
    const foodListContainer = document.getElementById('foodList-container');
    const foodMoodListSection = document.getElementById('foodMoodList');
    let currentMood = 'first';
    
    function displayFoodMood(page) {
        foodListContainer.innerHTML = '';
    
        foodMood[page].forEach(item => {
            const listItemHTML = `
            <li class="foodList-li">
                <h3>${item.title}</h3>
                <img src="${item.img}" alt="${item.title} image">
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
            back;
        }
        displayFoodMood(currentMood);
    });
    
    document.getElementById('skip-right').addEventListener('click', () => {
        if (currentMood === 'first') {
            currentMood = 'right';
        } else if (currentMood === 'left') {
            currentMood = 'first';
        } else {
            back;
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
});