document.addEventListener("DOMContentLoaded", function () {
    const searchBar = document.getElementById('search-bar');
    const recipes = document.querySelectorAll('.recipe');
    const newsBlock = document.getElementById('newsBlock');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const recipesSection = document.querySelector('.recipes');
    let activeFilter = null;

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

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');
            
            if (activeFilter === category) {
                recipes.forEach(recipe => {
                    recipe.classList.add('d-none');
                    recipe.classList.remove('d-active');
                });

                newsBlock.classList.add('d-active');
                newsBlock.classList.remove('d-none');

                recipesSection.classList.add('d-none');
                recipesSection.classList.remove('d-active');

                activeFilter = null;
            } else {
                activeFilter = category;
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
});
