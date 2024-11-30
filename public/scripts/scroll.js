let isScrolling;

window.addEventListener('scroll', () => {
  // Показываем скроллбар
  document.documentElement.classList.add('scrolling');
  document.body.classList.add('scrolling');

  // Таймер для скрытия скроллбара
  clearTimeout(isScrolling);
  isScrolling = setTimeout(() => {
    document.documentElement.classList.remove('scrolling');
    document.body.classList.remove('scrolling');
  }, 500); // Тайм-аут: 700 мс
});

// Скрываем скроллбар при загрузке страницы
document.documentElement.classList.remove('scrolling');
document.body.classList.remove('scrolling');
