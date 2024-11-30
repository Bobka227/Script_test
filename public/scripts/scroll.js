let isScrolling;

window.addEventListener('scroll', () => {
  document.documentElement.classList.add('scrolling');
  document.body.classList.add('scrolling');

  clearTimeout(isScrolling);
  isScrolling = setTimeout(() => {
    document.documentElement.classList.remove('scrolling');
    document.body.classList.remove('scrolling');
  }, 700); 
});

document.documentElement.classList.remove('scrolling');
document.body.classList.remove('scrolling');
