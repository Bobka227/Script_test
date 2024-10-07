document.addEventListener("DOMContentLoaded", function () {
    const btnBurgerMenu = document.getElementById('btnBurgerMenu');
    const crossBurgerMenu = document.getElementById('crossBurgerMenu');
    const menu = document.querySelector('.menu');

    btnBurgerMenu.addEventListener('click', function () {
        menu.classList.toggle('d-none');
        menu.classList.toggle('d-active');
        btnBurgerMenu.classList.toggle('d-none');
        crossBurgerMenu.classList.toggle('d-none');
    });

    crossBurgerMenu.addEventListener('click', function () {
        menu.classList.toggle('d-none');
        menu.classList.toggle('d-active');
        btnBurgerMenu.classList.toggle('d-none');
        crossBurgerMenu.classList.toggle('d-none');
    });
});

