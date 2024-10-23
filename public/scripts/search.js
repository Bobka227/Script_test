document.addEventListener("DOMContentLoaded", function () {
    const searchBar = document.getElementById('search-bar');
    const recipes = document.querySelectorAll('.recipe');
    const newsBlock = document.getElementById('newsBlock');
    // const filterButtons = document.querySelectorAll('.filter-btn');
    // const recipesSection = document.querySelector('.recipes');
    let activeFilter = null;
    let activeButton = null;

    const searchHistorySection = document.getElementById('searchHistorySection');
    const historyList = document.querySelector('.history-list');
    const prevButton = document.getElementById('prevBtn');
    const nextButton = document.getElementById('nextBtn');
    let currentIndex = 0;
    const visibleCount = 5;
    const recipesSection = document.querySelector('.recipes');
    const filterButtons = document.querySelectorAll('.filter-btn');

    // Функция для получения и отображения рецептов
    function fetchRecipes(filterType = '') {
        let url = '../recipes.php';
        if (filterType) {
            url += `?type=${filterType}`;
        }

        console.log(`Отправляем запрос на сервер с фильтром: ${filterType}`);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(`Получены рецепты для фильтра ${filterType}:`, data);

                // Очищаем предыдущие рецепты
                recipesSection.innerHTML = '';

                // Выводим рецепты
                data.forEach(recipe => {
                    const recipeDiv = document.createElement('div');
                    recipeDiv.classList.add('recipe');

                    recipeDiv.innerHTML = `
                        <h3>${recipe.name}</h3>
                        <img src="public/images/${recipe.image}" alt="${recipe.name}">
                        <p>Cooking Method: ${recipe.cooking_method}</p>
                        <p>Time Required: ${recipe.time_required} minutes</p>
                        <p>Type: ${recipe.type}</p>
                    `;

                    recipesSection.appendChild(recipeDiv);
                });
            })
            .catch(error => {
                console.error('Ошибка при запросе рецептов:', error);
            });
    }

    searchBar.addEventListener('input', function () {
        const query = searchBar.value.toLowerCase().trim();
        
        if (query) {
            recipesSection.classList.add('d-active');
            recipesSection.classList.remove('d-none');
            let hasSearchResults = false;

            recipes.forEach(recipe => {
                const title = recipe.querySelector('h3').textContent.toLowerCase();
                if (title.includes(query)) {
                    recipe.classList.add('d-active');
                    recipe.classList.remove('d-none');
                    hasSearchResults = true;
                } else {
                    recipe.classList.add('d-none');
                    recipe.classList.remove('d-active');
                }
            });

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

            recipes.forEach(recipe => {
                recipe.classList.add('d-none');
                recipe.classList.remove('d-active');
            });

            recipesSection.classList.add('d-none');
            recipesSection.classList.remove('d-active');
        }
    });

    recipes.forEach(recipe => {
        const titleElement = recipe.querySelector('h3');
        titleElement.addEventListener('click', function() {
            const query = this.textContent.trim();
            const imageURL = recipe.getElementsByClassName('history-item-img').src;
            addSearchQuery(query, imageURL);
            searchBar.value = '';
            showSearchHistorySection();
        });
    });

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filterType = this.getAttribute('data-category');
            fetchRecipes(filterType);

            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }

            if (activeFilter === filterType) {
                button.classList.remove('active');
                recipes.forEach(recipe => {
                    recipe.classList.add('d-none');
                    recipe.classList.remove('d-active');
                });

                newsBlock.classList.add('d-active');
                newsBlock.classList.remove('d-none');

                recipesSection.classList.add('d-none');
                recipesSection.classList.remove('d-active');

                activeFilter = null;
                activeButton = null;
            } else {
                button.classList.add('active');
                activeFilter = filterType;
                activeButton = button;

                recipesSection.classList.add('d-active');
                recipesSection.classList.remove('d-none');
                let hasFilteredRecipes = false;

                recipes.forEach(recipe => {
                    if (recipe.getAttribute('data-category') === filterType) {
                        recipe.classList.add('d-active');
                        recipe.classList.remove('d-none');
                        hasFilteredRecipes = true;
                    } else {
                        recipe.classList.add('d-none');
                        recipe.classList.remove('d-active');
                    }
                });

                if (hasFilteredRecipes) {
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
    fetchRecipes();
});
