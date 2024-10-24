document.addEventListener("DOMContentLoaded", function () {
    const searchBar = document.getElementById('search-bar');
    const recipes = document.querySelectorAll('.recipe');
    const newsBlock = document.getElementById('newsBlock');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const recipesSection = document.querySelector('.recipes');
    let activeFilter = null;
    let activeButton = null;

    const recipesAll = [
        {
            title: "Grilled Chicken Salad",
            image: "images/grilled_chicken_salad.jpg",
            ingredients: ["Chicken breast", "Lettuce", "Tomatoes", "Cucumber", "Olive oil", "Lemon juice"],
            description: "A healthy and delicious salad with grilled chicken, fresh vegetables, and a light lemon dressing.",
            category: "lunch"
        },
        {
            title: "Spaghetti Carbonara",
            image: "images/spaghetti_carbonara.jpg",
            ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
            description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
            category: "dinner"
        },
        {
            title: "y",
            image: "images/spaghetti_carbonara.jpg",
            ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
            description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
            category: "dinner"
        },
        {
            title: "z",
            image: "images/spaghetti_carbonara.jpg",
            ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
            description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
            category: "dinner"
        },
        {
            title: "k",
            image: "images/spaghetti_carbonara.jpg",
            ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
            description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
            category: "dinner"
        },
        {
            title: "Vegan Avocado Toast",
            image: "images/avocado_toast.jpg",
            ingredients: ["Avocado", "Whole-grain bread", "Lime", "Salt", "Pepper", "Chili flakes"],
            description: "A simple and delicious vegan toast with mashed avocado, lime, and chili flakes for a little kick.",
            category: "breakfast"
        },
        {
            title: "Beef Tacos",
            image: "images/beef_tacos.jpg",
            ingredients: ["Ground beef", "Taco shells", "Lettuce", "Cheese", "Sour cream", "Salsa"],
            description: "Tasty beef tacos loaded with fresh toppings and packed with flavor, perfect for a quick lunch or dinner.",
            category: "lunch"
        },
        {
            title: "Pancakes with Maple Syrup",
            image: "images/pancakes.jpg",
            ingredients: ["Flour", "Milk", "Eggs", "Baking powder", "Butter", "Maple syrup"],
            description: "Classic fluffy pancakes drizzled with maple syrup, ideal for a weekend breakfast or brunch.",
            category: "breakfast"
        },
        {
            title: "Chicken Stir Fry",
            image: "images/chicken_stir_fry.jpg",
            ingredients: ["Chicken breast", "Broccoli", "Carrots", "Soy sauce", "Garlic", "Ginger"],
            description: "A quick and easy stir-fry with tender chicken and vegetables in a savory soy-garlic sauce.",
            category: "dinner"
        }
    ];    

    const searchHistorySection = document.getElementById('searchHistorySection');
    const historyList = document.querySelector('.history-list');
    const prevButton = document.getElementById('prevBtn');
    const nextButton = document.getElementById('nextBtn');
    let currentIndex = 0;
    const visibleCount = 5;

    searchBar.addEventListener('input', function () {
        const recipesList = document.querySelector('.recipes-list');
        recipesList.innerHTML = '';

        const query = searchBar.value.toLowerCase().trim();
        
        if (query) {
            recipesSection.classList.add('d-active');
            recipesSection.classList.remove('d-none');
            const filteredRecipes = recipesAll.filter(recipe => recipe.title.toLowerCase().includes(query));
            let hasSearchResults = false;

            if (filteredRecipes.length > 0) {
                addRecipeCards(filteredRecipes);
                hasSearchResults = true;
            }

            if (hasSearchResults) {
                newsBlock.classList.add('d-none');
                newsBlock.classList.remove('d-active');
            } else {
                newsBlock.classList.add('d-active');
                newsBlock.classList.remove('d-none');
            }
        } else {
            newsBlock.classList.add('d-active');
            newsBlock.classList.remove('d-none');

            recipesSection.classList.add('d-none');
            recipesSection.classList.remove('d-active');
        }
    });

    function addRecipeCards(recipes) {
        const recipesList = document.querySelector('.recipes-list');
        recipesList.innerHTML = '';
    
        recipes.forEach(recipe => {
            const recipeCard = `
                <li class="recipe" data-category="${recipe.category}">
                    <div class="recipe-front">
                        <img src="${recipe.image}" alt="${recipe.title} image" class="recipe-image">
                        <h3 class="recipe-title">${recipe.title}</h3>
                    </div>
                    <div class="recipe-back d-none">
                        <h3 class="recipes-ingredients">Ingredients:</h3>
                        <ul class="recipes-ingredients-list">
                            ${recipe.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
                        </ul>
                        <h3 class="recipes-recipe">Description:</h3>
                        <p>${recipe.description}</p>
                    </div>
                </li>

            `;
            
            recipesList.innerHTML += recipeCard;
        });
    }

    function flipCard(card) {
        const front = card.querySelector('.recipe-front');
        const back = card.querySelector('.recipe-back');

        if (front.classList.contains('d-none')) {
            front.classList.remove('d-none');
            back.classList.add('d-none');
        } else {
            front.classList.add('d-none');
            back.classList.remove('d-none');
        }
    }

    document.querySelector('.recipes-list').addEventListener('click', function (e) {
        const card = e.target.closest('.recipe');
        if (card) {
            flipCard(card);
            
            const query = card.querySelector('.recipe-title').textContent.trim();
            const imageURL = card.querySelector('.recipe-image').src;
            addSearchQuery(query, imageURL);
        }
    });

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');

            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }

            if (activeFilter === category) {
                button.classList.remove('active');
                newsBlock.classList.add('d-active');
                newsBlock.classList.remove('d-none');
                recipesSection.classList.add('d-none');
                recipesSection.classList.remove('d-active');

                activeFilter = null;
                activeButton = null;

                const recipesList = document.querySelector('.recipes-list');
                recipesList.innerHTML = '';
            } else {
                button.classList.add('active');
                activeFilter = category;
                activeButton = button;

                recipesSection.classList.add('d-active');
                recipesSection.classList.remove('d-none');
                const filteredRecipes = recipesAll.filter(recipe => recipe.category === category);

                if (filteredRecipes.length > 0) {
                    addRecipeCards(filteredRecipes);
                    newsBlock.classList.add('d-none');
                    newsBlock.classList.remove('d-active');
                } else {
                    newsBlock.classList.add('d-active');
                    newsBlock.classList.remove('d-none');
                }
            }
        });
    });

    function updateHistoryDisplay() {
        const history = getSearchHistory();
        historyList.innerHTML = '';
    
        history.slice(currentIndex, currentIndex + visibleCount).forEach((item, index) => {
            historyList.innerHTML += `
            <li class="history-item">
                <div class="history-item-image">
                    <img src="${item.image}" alt="history item" class="history-item-img">
                </div>
                <div class="history-item-name">
                    <p>${item.query}</p>
                </div>
            </li>
            `;
        });
    
        prevButton.style.display = currentIndex > 0 ? 'block' : 'none';
        nextButton.style.display = currentIndex + visibleCount < history.length ? 'block' : 'none';
    }

    function getSearchHistory() {
        return JSON.parse(localStorage.getItem('searchHistory')) || [];
    }

    function saveSearchHistory(history) {
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }

    function addSearchQuery(query, imageURL) {
        let history = getSearchHistory();
        const existingIndex = history.findIndex(item => item.query === query);
        
        if (existingIndex !== -1) {
            history.splice(existingIndex, 1);
        }
        
        history.unshift({ query, image: imageURL });
        
        if (history.length > 20) {
            history.pop();
        }
        
        saveSearchHistory(history);
        updateHistoryDisplay();
    }

    prevButton.addEventListener('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            updateHistoryDisplay();
        }
    });

    nextButton.addEventListener('click', function () {
        if (currentIndex + visibleCount < getSearchHistory().length) {
            currentIndex++;
            updateHistoryDisplay();
        }
    });

    updateHistoryDisplay();

    const initialHistory = getSearchHistory();
    if (initialHistory.length > 0) {
        showSearchHistorySection();
        renderSearchHistory(initialHistory);
    } else {
        hideSearchHistorySection();
    }

    function showSearchHistorySection() {
        searchHistorySection.classList.remove('d-none');
    }

    function hideSearchHistorySection() {
        searchHistorySection.classList.add('d-none');
    }

    function renderSearchHistory(history) {
        const historyList = document.getElementById('history-list');
        historyList.innerHTML = '';
    
        history.forEach((item) => {
            const listItem = document.createElement('li');
            listItem.className = 'history-item';
    
            listItem.innerHTML = `
                <div class="history-item-image">
                    <img src="${item.image}" alt="history item" class="history-item-img">
                </div>
                <div class="history-item-name">
                    <p>${item.query}</p>
                </div>
            `;
    
            historyList.appendChild(listItem);
        });
    }

    historyList.addEventListener('click', function(event) {
        const clickedElement = event.target;
        if (clickedElement.classList.contains('history-item-img')) {
            const query = clickedElement.closest('.history-item').querySelector('.history-item-name p').textContent;
            searchBar.value = query;
            searchBar.dispatchEvent(new Event('input'));
        }
        if (clickedElement.tagName === 'P' && clickedElement.closest('.history-item-name')) {
            const query = clickedElement.textContent;
            searchBar.value = query;
            searchBar.dispatchEvent(new Event('input'));
        }
    });
});
