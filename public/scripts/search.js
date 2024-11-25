document.addEventListener("DOMContentLoaded", async function () {
    const searchBar = document.getElementById('search-bar');
    const newsBlock = document.getElementById('newsBlock');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const recipesSection = document.querySelector('.recipes');
    const searchHistorySection = document.getElementById('searchHistorySection');
    const historyList = document.querySelector('.history-list');
    const prevButton = document.getElementById('prevBtn');
    const nextButton = document.getElementById('nextBtn');
	const recipesList = document.querySelector('.recipes-list');
	let activeFilter = null;
    let activeButton = null;
    let currentIndex = 0;
    let visibleCount = 5;

	async function fetchRecipes(category = null, query = null) {
		let url = '../recipes.php';
		if (category) {
			url += `?type=${category}`;
		} else if (query) {
			url += `?query=${query}`;
		}

		try {
			const response = await fetch(url);
			const text = await response.text();
			let recipes;

			try {
				recipes = JSON.parse(text);
			} catch (e) {
				console.error('Ошибка парсинга JSON: ', e);
				console.error('Ответ сервера: ', text);
				recipesList.innerHTML =
					'<p>Ошибка при загрузке рецептов. Пожалуйста, попробуйте позже.</p>';
				return;
			}
			addRecipeCards(recipes);

		} catch (error) {
			console.error('Ошибка загрузки рецептов: ', error);
			recipesList.innerHTML =
				'<p>Ошибка при загрузке рецептов. Пожалуйста, попробуйте позже.</p>';
		}
	}

	searchBar.addEventListener('input', async function () {
		recipesList.innerHTML = '';
		const query = searchBar.value.toLowerCase().trim();
	
		if (query) {
			recipesSection.classList.add('d-active');
			recipesSection.classList.remove('d-none');
			await fetchRecipes(null, query);
			newsBlock.classList.add('d-none');
			newsBlock.classList.remove('d-active');
		} else {
			newsBlock.classList.add('d-active');
			newsBlock.classList.remove('d-none');
			recipesSection.classList.add('d-none');
			recipesSection.classList.remove('d-active');

			if (activeFilter) {
				await fetchRecipes(activeFilter);
			} else {
				recipesList.innerHTML = '';
			}
		}
	});
	

	function addRecipeCards(recipes) {
        recipesList.innerHTML = '';

		if (!Array.isArray(recipes) || recipes.length === 0) {
			recipesList.innerHTML = '<p>Рецепты не найдены.</p>';
			return;
		}

        recipes.forEach(recipe => {
			const title = recipe.name;
			const description = recipe.pdf_link
				? `<a href="${recipe.pdf_link}" target="_blank">Recipe link</a>`
				: 'Описание отсутствует'
			const image = recipe.image || 'placeholder.jpg';
			const qrCodeLink = recipe.qr_code_link
				? `<img src="data:image/png;base64,${recipe.qr_code_link}" alt="QR-код для ${title}" class="qr-code" style="object-fit: cover; width: 100%; height: 100%;">`
				: '';

				const recipeCard = `
                <li class="recipe" data-id="${recipe.id}" data-name="${title}">
                    <div class="recipe-card">
                        <div class="recipe-front">
                            <img src="${image}" alt="${title} image" class="recipe-image" style="object-fit: cover; width: 100%; height: 100%;">
                            <h3 class="recipe-title">${title}</h3>
                        </div>
                        <div class="recipe-back d-none">
                            <h3 class="recipe-title">${title}</h3>
                            ${qrCodeLink}
                            <p class="recipe-link">${description}</p>
                        </div>
                    </div>
                </li>
            `

            recipesList.innerHTML += recipeCard;
        });
    }

    function updateVisibleCount() {
        if (window.innerWidth <= 560) {
            visibleCount = 3;
        } else {
            visibleCount = 5;
        }
        updateHistoryDisplay();
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
			const recipeName = card.dataset.name;
			const recipeImage = card.querySelector('.recipe-image').src;
			addSearchQuery(recipeName, recipeImage);
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
        button.addEventListener('click', async function () {
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

                recipesList.innerHTML = '';
            } else {
                button.classList.add('active');
                activeFilter = category;
                activeButton = button;

                recipesSection.classList.add('d-active');
                recipesSection.classList.remove('d-none');
				await fetchRecipes(category);
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

    historyList.addEventListener('click', function (event) {
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

    window.addEventListener('resize', updateVisibleCount);
    updateVisibleCount();

    updateHistoryDisplay();
});

