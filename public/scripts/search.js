document.addEventListener("DOMContentLoaded", function () {
    const searchBar = document.getElementById('search-bar');
    const recipes = document.querySelectorAll('.recipe');
    const newsBlock = document.getElementById('newsBlock');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const recipesSection = document.querySelector('.recipes');
    let activeFilter = null;
    let activeButton = null;

    const searchHistorySection = document.getElementById('searchHistorySection');
    const historyList = document.querySelector('.history-list');
    const prevButton = document.getElementById('prevBtn');
    const nextButton = document.getElementById('nextBtn');
    let currentIndex = 0;
    const visibleCount = 5;

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
            addSearchQuery(query);
            searchBar.value = '';
            showSearchHistorySection();
        });
    });

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');

            if (activeButton && activeButton !== button) {
                activeButton.classList.remove('active');
            }

            if (activeFilter === category) {
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
                activeFilter = category;
                activeButton = button;

                recipesSection.classList.add('d-active');
                recipesSection.classList.remove('d-none');
                let hasFilteredRecipes = false;

                recipes.forEach(recipe => {
                    if (recipe.getAttribute('data-category') === category) {
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
            const li = document.createElement('li');
            li.classList.add('history-item');
            li.textContent = item;
            historyList.appendChild(li);
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

    function addSearchQuery(query) {
        let history = getSearchHistory();
        const existingIndex = history.indexOf(query);
    
        if (existingIndex !== -1) {
            history.splice(existingIndex, 1);
        }
    
        history.push(query);
    
        if (history.length > 20) {
            history.shift();
        }
    
        saveSearchHistory(history);
        updateHistoryDisplay();
    }

    searchBar.addEventListener('keyup', function (event) {
        if (event.key === 'Enter') {
            const query = searchBar.value.trim();
            if (query) {
                addSearchQuery(query);
                searchBar.value = '';
                currentIndex = Math.max(0, getSearchHistory().length - visibleCount);
                updateHistoryDisplay();
            }
            showSearchHistorySection();
        }
    });

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

    searchBar.addEventListener('change', function () {
        const query = searchBar.value.toLowerCase().trim();
        if (query) {
            addSearchQuery(query);
            renderSearchHistory(getSearchHistory());
            showSearchHistorySection();
        }
    });

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
            const li = document.createElement('li');
            li.className = 'history-item';
            li.textContent = item;
            historyList.appendChild(li);
        });
    }
});
