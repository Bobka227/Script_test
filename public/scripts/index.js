document.addEventListener("DOMContentLoaded", function () {
    
    // modal

    const modal = document.getElementById('modal');
    const btnGetStarted = document.querySelector('.btnGetStarted');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnLogin = document.getElementById('btnLogin');
    const btnSignup = document.getElementById('btnSignup');

    btnGetStarted.addEventListener('click', () => {
        modal.classList.remove('d-none');
    });

    btnCloseModal.addEventListener('click', () => {
        modal.classList.add('d-none');
    });

    btnLogin.addEventListener('click', () => {
        window.location.href = 'pages/registration.html';
    });

    btnSignup.addEventListener('click', () => {
        window.location.href = 'pages/registration.html';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('d-none');
        }
    });
});