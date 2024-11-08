// document.addEventListener("DOMContentLoaded", async function () {
//     const searchBar = document.getElementById('search-bar');
//     const newsBlock = document.getElementById('newsBlock');
//     const filterButtons = document.querySelectorAll('.filter-btn');
//     const recipesSection = document.querySelector('.recipes');
//     let activeFilter = null;
//     let activeButton = null;
//     const recipesAll = [
//         {
//             title: "Grilled Chicken Salad",
//             image: "images/grilled_chicken_salad.jpg",
//             ingredients: ["Chicken breast", "Lettuce", "Tomatoes", "Cucumber", "Olive oil", "Lemon juice"],
//             description: 'A healthy and delicious salad with grilled chicken, fresh vegetables, and a light lemon dressing.',
//             category: "lunch"
//         },
//         {
//             title: "Spaghetti Carbonara",
//             image: "images/spaghetti_carbonara.jpg",
//             ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
//             description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
//             category: "dinner"
//         },
//         {
//             title: "y",
//             image: "images/spaghetti_carbonara.jpg",
//             ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
//             description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
//             category: "dinner"
//         },
//         {
//             title: "z",
//             image: "images/spaghetti_carbonara.jpg",
//             ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
//             description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
//             category: "dinner"
//         },
//         {
//             title: "k",
//             image: "images/spaghetti_carbonara.jpg",
//             ingredients: ["Spaghetti", "Bacon", "Eggs", "Parmesan", "Black pepper"],
//             description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Quick and satisfying.",
//             category: "dinner"
//         },
//         {
//             title: "Vegan Avocado Toast",
//             image: "images/avocado_toast.jpg",
//             ingredients: ["Avocado", "Whole-grain bread", "Lime", "Salt", "Pepper", "Chili flakes"],
//             description: "A simple and delicious vegan toast with mashed avocado, lime, and chili flakes for a little kick.",
//             category: "breakfast"
//         },
//         {
//             title: "Beef Tacos",
//             image: "images/beef_tacos.jpg",
//             ingredients: ["Ground beef", "Taco shells", "Lettuce", "Cheese", "Sour cream", "Salsa"],
//             description: "Tasty beef tacos loaded with fresh toppings and packed with flavor, perfect for a quick lunch or dinner.",
//             category: "lunch"
//         },
//         {
//             title: "Pancakes with Maple Syrup",
//             image: "images/pancakes.jpg",
//             ingredients: ["Flour", "Milk", "Eggs", "Baking powder", "Butter", "Maple syrup"],
//             description: "Classic fluffy pancakes drizzled with maple syrup, ideal for a weekend breakfast or brunch.",
//             category: "breakfast"
//         },
//         {
//             title: "Chicken Stir Fry",
//             image: "images/chicken_stir_fry.jpg",
//             ingredients: ["Chicken breast", "Broccoli", "Carrots", "Soy sauce", "Garlic", "Ginger"],
//             description: "A quick and easy stir-fry with tender chicken and vegetables in a savory soy-garlic sauce.",
//             category: "dinner"
//         }
//     ];

//     const searchHistorySection = document.getElementById('searchHistorySection');
//     const historyList = document.querySelector('.history-list');
//     const prevButton = document.getElementById('prevBtn');
//     const nextButton = document.getElementById('nextBtn');
//     let currentIndex = 0;
//     let visibleCount = 5;

//     searchBar.addEventListener('input', function () {
//         const recipesList = document.querySelector('.recipes-list');
//         recipesList.innerHTML = '';

//         const query = searchBar.value.toLowerCase().trim();

//         if (query) {
//             recipesSection.classList.add('d-active');
//             recipesSection.classList.remove('d-none');
//             const filteredRecipes = recipesAll.filter(recipe => recipe.title.toLowerCase().includes(query));
//             let hasSearchResults = false;

//             if (filteredRecipes.length > 0) {
//                 addRecipeCards(filteredRecipes);
//                 hasSearchResults = true;
//             }

//             if (hasSearchResults) {
//                 newsBlock.classList.add('d-none');
//                 newsBlock.classList.remove('d-active');
//             } else {
//                 newsBlock.classList.add('d-active');
//                 newsBlock.classList.remove('d-none');
//             }
//         } else {
//             newsBlock.classList.add('d-active');
//             newsBlock.classList.remove('d-none');

//             recipesSection.classList.add('d-none');
//             recipesSection.classList.remove('d-active');
//         }
//     });

//     function addRecipeCards(recipes) {
//         const recipesList = document.querySelector('.recipes-list');
//         recipesList.innerHTML = '';

//         recipes.forEach(recipe => {
//             const recipeCard = `
//                 <li class="recipe" data-category="${recipe.category}">
//                     <div class="recipe-front">
//                         <img src="${recipe.image}" alt="${recipe.title} image" class="recipe-image">
//                         <h3 class="recipe-title">${recipe.title}</h3>
//                     </div>
//                     <div class="recipe-back d-none">
//                         <h3 class="recipes-ingredients">Ingredients:</h3>
//                         <ul class="recipes-ingredients-list">
//                             ${recipe.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
//                         </ul>
//                         <h3 class="recipes-recipe">Description:</h3>
//                         <p>${recipe.description}</p>
//                     </div>
//                 </li>

//             `;

//             recipesList.innerHTML += recipeCard;
//         });
//     }

//     function updateVisibleCount() {
//         if (window.innerWidth <= 560) {
//             visibleCount = 3;
//         } else {
//             visibleCount = 5;
//         }
//         updateHistoryDisplay();
//     }

//     function flipCard(card) {
//         const front = card.querySelector('.recipe-front');
//         const back = card.querySelector('.recipe-back');

//         if (front.classList.contains('d-none')) {
//             front.classList.remove('d-none');
//             back.classList.add('d-none');
//         } else {
//             front.classList.add('d-none');
//             back.classList.remove('d-none');
//         }
//     }

//     document.querySelector('.recipes-list').addEventListener('click', function (e) {
//         const card = e.target.closest('.recipe');
//         if (card) {
//             flipCard(card);

//             const query = card.querySelector('.recipe-title').textContent.trim();
//             const imageURL = card.querySelector('.recipe-image').src;
//             addSearchQuery(query, imageURL);
//         }
//     });

//     filterButtons.forEach(button => {
//         button.addEventListener('click', function () {
//             const category = this.getAttribute('data-category');

//             if (activeButton && activeButton !== button) {
//                 activeButton.classList.remove('active');
//             }

//             if (activeFilter === category) {
//                 button.classList.remove('active');
//                 newsBlock.classList.add('d-active');
//                 newsBlock.classList.remove('d-none');
//                 recipesSection.classList.add('d-none');
//                 recipesSection.classList.remove('d-active');

//                 activeFilter = null;
//                 activeButton = null;

//                 const recipesList = document.querySelector('.recipes-list');
//                 recipesList.innerHTML = '';
//             } else {
//                 button.classList.add('active');
//                 activeFilter = category;
//                 activeButton = button;

//                 recipesSection.classList.add('d-active');
//                 recipesSection.classList.remove('d-none');
//                 const filteredRecipes = recipesAll.filter(recipe => recipe.category === category);

//                 if (filteredRecipes.length > 0) {
//                     addRecipeCards(filteredRecipes);
//                     newsBlock.classList.add('d-none');
//                     newsBlock.classList.remove('d-active');
//                 } else {
//                     newsBlock.classList.add('d-active');
//                     newsBlock.classList.remove('d-none');
//                 }
//             }
//         });
//     });

//     function updateHistoryDisplay() {
//         const history = getSearchHistory();
//         historyList.innerHTML = '';

//         history.slice(currentIndex, currentIndex + visibleCount).forEach((item, index) => {
//             historyList.innerHTML += `
//             <li class="history-item">
//                 <div class="history-item-image">
//                     <img src="${item.image}" alt="history item" class="history-item-img">
//                 </div>
//                 <div class="history-item-name">
//                     <p>${item.query}</p>
//                 </div>
//             </li>
//             `;
//         });

//         prevButton.style.display = currentIndex > 0 ? 'block' : 'none';
//         nextButton.style.display = currentIndex + visibleCount < history.length ? 'block' : 'none';
//     }

//     function getSearchHistory() {
//         return JSON.parse(localStorage.getItem('searchHistory')) || [];
//     }

//     function saveSearchHistory(history) {
//         localStorage.setItem('searchHistory', JSON.stringify(history));
//     }

//     function addSearchQuery(query, imageURL) {
//         let history = getSearchHistory();
//         const existingIndex = history.findIndex(item => item.query === query);

//         if (existingIndex !== -1) {
//             history.splice(existingIndex, 1);
//         }

//         history.unshift({query, image: imageURL});

//         if (history.length > 20) {
//             history.pop();
//         }

//         saveSearchHistory(history);
//         updateHistoryDisplay();
//     }

//     prevButton.addEventListener('click', function () {
//         if (currentIndex > 0) {
//             currentIndex--;
//             updateHistoryDisplay();
//         }
//     });

//     nextButton.addEventListener('click', function () {
//         if (currentIndex + visibleCount < getSearchHistory().length) {
//             currentIndex++;
//             updateHistoryDisplay();
//         }
//     });

//     const initialHistory = getSearchHistory();
//     if (initialHistory.length > 0) {
//         showSearchHistorySection();
//         renderSearchHistory(initialHistory);
//     } else {
//         hideSearchHistorySection();
//     }

//     function showSearchHistorySection() {
//         searchHistorySection.classList.remove('d-none');
//     }

//     function hideSearchHistorySection() {
//         searchHistorySection.classList.add('d-none');
//     }

//     function renderSearchHistory(history) {
//         const historyList = document.getElementById('history-list');
//         historyList.innerHTML = '';

//         history.forEach((item) => {
//             const listItem = document.createElement('li');
//             listItem.className = 'history-item';

//             listItem.innerHTML = `
//                 <div class="history-item-image">
//                     <img src="${item.image}" alt="history item" class="history-item-img">
//                 </div>
//                 <div class="history-item-name">
//                     <p>${item.query}</p>
//                 </div>
//             `;

//             historyList.appendChild(listItem);
//         });
//     }

//     historyList.addEventListener('click', function (event) {
//         const clickedElement = event.target;
//         if (clickedElement.classList.contains('history-item-img')) {
//             const query = clickedElement.closest('.history-item').querySelector('.history-item-name p').textContent;
//             searchBar.value = query;
//             searchBar.dispatchEvent(new Event('input'));
//         }
//         if (clickedElement.tagName === 'P' && clickedElement.closest('.history-item-name')) {
//             const query = clickedElement.textContent;
//             searchBar.value = query;
//             searchBar.dispatchEvent(new Event('input'));
//         }
//     });

//     window.addEventListener('resize', updateVisibleCount);
//     updateVisibleCount();

//     updateHistoryDisplay();
// });

document.addEventListener('DOMContentLoaded', async function () {
	const searchBar = document.getElementById('search-bar')
	const filterButtons = document.querySelectorAll('.filter-btn')
	const recipesSection = document.querySelector('.recipes')
	const recipesList = document.querySelector('.recipes-list')
	const searchHistorySection = document.getElementById('searchHistorySection')
	const historyList = document.querySelector('.history-list')
	const prevButton = document.getElementById('prevBtn')
	const nextButton = document.getElementById('nextBtn')
	let activeFilter = null
	let activeButton = null
	let currentIndex = 0
	let visibleCount = 5

	// Слушатель событий для кнопок фильтрации
	filterButtons.forEach(button => {
		button.addEventListener('click', async function () {
			const category = this.getAttribute('data-category')

			if (activeButton && activeButton !== button) {
				activeButton.classList.remove('active')
			}

			if (activeFilter === category) {
				button.classList.remove('active')
				activeFilter = null
				activeButton = null
				recipesSection.classList.add('d-none')
				recipesSection.classList.remove('d-active')
				recipesList.innerHTML = ''
			} else {
				button.classList.add('active')
				activeFilter = category
				activeButton = button
				recipesSection.classList.add('d-active')
				recipesSection.classList.remove('d-none')
				await fetchRecipes(category)
			}
		})
	})

	// Слушатель событий для поисковой строки
	searchBar.addEventListener('input', async function () {
		const query = searchBar.value.toLowerCase().trim()
		if (query) {
			recipesSection.classList.add('d-active')
			recipesSection.classList.remove('d-none')
			await fetchRecipes(null, query)
		} else if (activeFilter) {
			await fetchRecipes(activeFilter)
		} else {
			recipesList.innerHTML = ''
			recipesSection.classList.add('d-none')
			recipesSection.classList.remove('d-active')
		}
	})

	// Функция для получения рецептов с сервера
	async function fetchRecipes(category = null, query = null) {
		let url = '../recipes.php'
		if (category) {
			url += `?type=${category}`
		} else if (query) {
			url += `?query=${query}`
		}

		try {
			const response = await fetch(url)
			const text = await response.text()
			let recipes

			try {
				recipes = JSON.parse(text)
			} catch (e) {
				console.error('Ошибка парсинга JSON: ', e)
				console.error('Ответ сервера: ', text)
				recipesList.innerHTML =
					'<p>Ошибка при загрузке рецептов. Пожалуйста, попробуйте позже.</p>'
				return
			}

			displayRecipes(recipes)
		} catch (error) {
			console.error('Ошибка загрузки рецептов: ', error)
			recipesList.innerHTML =
				'<p>Ошибка при загрузке рецептов. Пожалуйста, попробуйте позже.</p>'
		}
	}

	// Функция для отображения полученных рецептов
	function displayRecipes(recipes) {
		recipesList.innerHTML = ''

		if (!Array.isArray(recipes) || recipes.length === 0) {
			recipesList.innerHTML = '<p>Рецепты не найдены.</p>'
			return
		}

		recipes.forEach(recipe => {
			const title = recipe.name
			const description = recipe.pdf_link
				? `<a href="${recipe.pdf_link}" target="_blank">Recipe link</a>`
				: 'Описание отсутствует'
			const image = recipe.image || 'placeholder.jpg'
			const qrCodeLink = recipe.qr_code_link
				? `data:image/png;base64,${recipe.qr_code_link}`
				: null

			const recipeCard = `
                <li class="recipe" data-category="${recipe.type || ''}">
                    <div class="recipe-card">
                        <div class="recipe-front">
                            <img src="${image}" alt="${title} image" class="recipe-image">
                            <h3 class="recipe-title">${title}</h3>
                        </div>
                        <div class="recipe-back d-none">
                            <h3 class="recipe-title">${title}</h3>
                            ${
															qrCodeLink
																? `<img src="${qrCodeLink}" alt="QR-код для ${title}" class="qr-code">`
																: ''
														}
                            <p class="recipe-link">${description}</p>
                        </div>
                    </div>
                </li>
            `

			recipesList.innerHTML += recipeCard
		})

		// Добавление событий для переворота карточек
		const recipeCards = document.querySelectorAll('.recipe')
		recipeCards.forEach(card => {
			card.addEventListener('click', function () {
				const front = this.querySelector('.recipe-front')
				const back = this.querySelector('.recipe-back')

				if (front.classList.contains('d-none')) {
					front.classList.remove('d-none')
					back.classList.add('d-none')
				} else {
					front.classList.add('d-none')
					back.classList.remove('d-none')
				}
			})
		})
	}

	// Функция для обновления видимого количества в зависимости от размера окна
	function updateVisibleCount() {
		if (window.innerWidth <= 560) {
			visibleCount = 3
		} else {
			visibleCount = 5
		}
		updateHistoryDisplay()
	}

	// Функция для обновления отображения истории поиска
	function updateHistoryDisplay() {
		const history = getSearchHistory()
		historyList.innerHTML = ''

		history
			.slice(currentIndex, currentIndex + visibleCount)
			.forEach((item, index) => {
				historyList.innerHTML += `
            <li class="history-item">
                <div class="history-item-image">
                    <img src="${item.image}" alt="history item" class="history-item-img">
                </div>
                <div class="history-item-name">
                    <p>${item.query}</p>
                </div>
            </li>
            `
			})

		prevButton.style.display = currentIndex > 0 ? 'block' : 'none'
		nextButton.style.display =
			currentIndex + visibleCount < history.length ? 'block' : 'none'
	}

	// Функции для получения, сохранения и добавления в историю поиска
	function getSearchHistory() {
		return JSON.parse(localStorage.getItem('searchHistory')) || []
	}

	function saveSearchHistory(history) {
		localStorage.setItem('searchHistory', JSON.stringify(history))
	}

	function addSearchQuery(query, imageURL) {
		let history = getSearchHistory()
		const existingIndex = history.findIndex(item => item.query === query)

		if (existingIndex !== -1) {
			history.splice(existingIndex, 1)
		}

		history.unshift({ query, image: imageURL })

		if (history.length > 20) {
			history.pop()
		}

		saveSearchHistory(history)
		updateHistoryDisplay()
	}

	// Слушатель событий для кнопок предыдущей и следующей в истории
	prevButton.addEventListener('click', function () {
		if (currentIndex > 0) {
			currentIndex--
			updateHistoryDisplay()
		}
	})

	nextButton.addEventListener('click', function () {
		if (currentIndex + visibleCount < getSearchHistory().length) {
			currentIndex++
			updateHistoryDisplay()
		}
	})

	// Инициализация отображения истории
	const initialHistory = getSearchHistory()
	if (initialHistory.length > 0) {
		searchHistorySection.classList.remove('d-none')
		updateHistoryDisplay()
	}

	window.addEventListener('resize', updateVisibleCount)
	updateVisibleCount()
})


