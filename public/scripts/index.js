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
        // Отправляем запрос на проверку авторизации
        fetch('check_aut.php')
            .then(response => response.json())
            .then(data => {
                if (data.authenticated) {
                    // Если пользователь авторизован, перенаправляем на mood.php
                    window.location.href = 'pages/mood.php';
                } else {
                    // Если пользователь не авторизован, перенаправляем на register.php
                    window.location.href = 'pages/register.php';
                }
            })
            .catch(error => {
                console.error('Ошибка проверки авторизации:', error);
                alert('Ошибка! Попробуйте ещё раз.');
            });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('modal-none');
            modal.classList.remove('modal-show');
        }
    });
});
