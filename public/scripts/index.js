document.addEventListener("DOMContentLoaded", function () {
    
    const modal = document.getElementById('modal');
    const btnGetStarted = document.querySelector('.btnGetStarted');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnLogin = document.getElementById('btnLogin');

    btnGetStarted.addEventListener('click', () => {
        modal.classList.remove('modal-none');
        modal.classList.add('modal-show');
    });

    btnCloseModal.addEventListener('click', () => {
        modal.classList.add('modal-none');
        modal.classList.remove('modal-show');
    });

    btnLogin.addEventListener('click', () => {
        window.location.href = 'pages/register.php';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('modal-none');
            modal.classList.remove('modal-show');
        }
    });
});