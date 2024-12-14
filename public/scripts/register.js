document.addEventListener("DOMContentLoaded", function () {
    let switchCtn = document.querySelector("#switch-cnt");
    let switchC1 = document.querySelector("#switch-c1");
    let switchC2 = document.querySelector("#switch-c2");
    let switchCircle = document.querySelectorAll(".switch__circle");
    let switchBtn = document.querySelectorAll(".switch-btn");
    let aContainer = document.querySelector("#a-container");
    let bContainer = document.querySelector("#b-container");
    let allButtons = document.querySelectorAll(".submit");

    let getButtons;

    let changeForm = (e) => {

        switchCtn.classList.add("is-gx");
        setTimeout(function(){
            switchCtn.classList.remove("is-gx");
        }, 1500)

        switchCtn.classList.toggle("is-txr");
        switchCircle[0].classList.toggle("is-txr");
        switchCircle[1].classList.toggle("is-txr");

        switchC1.classList.toggle("is-hidden");
        switchC2.classList.toggle("is-hidden");
        aContainer.classList.toggle("is-txl");
        bContainer.classList.toggle("is-txl");
        bContainer.classList.toggle("is-z200");
    }

    let mainF = (e) => {
        for (var i = 0; i < allButtons.length; i++)
            allButtons[i].addEventListener("click", getButtons );
        for (var i = 0; i < switchBtn.length; i++)
            switchBtn[i].addEventListener("click", changeForm)
    }

    window.addEventListener("load", mainF);
});


document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;

        if (password === "") {
            passwordError.style.display = 'none';
        } else {
            const passwordValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(password);
            if (!passwordValid) {
                passwordError.style.display = 'block';
            } else {
                passwordError.style.display = 'none';
            }
        }
    });
});

document.querySelector('form').addEventListener('submit', function (event) {
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');

    const passwordValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(passwordInput.value);

    if (!passwordValid) {
        passwordError.style.display = 'block';
        event.preventDefault(); // Останавливаем отправку формы
    }
});

function togglePassword() {
    const passwordField = document.getElementById('password');
    passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
}

// Функция для валидации каждого поля
function validateField(id, condition, errorMessage) {
    const field = document.getElementById(id);
    const errorField = document.getElementById(`${id}-error`);
    if (!condition(field.value)) {
        errorField.textContent = errorMessage;
        return false;
    } else {
        errorField.textContent = '';
        return true;
    }
}

document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Останавливаем отправку формы

    let isValid = true;

    // Проверка каждого поля
    isValid &= validateField('username', value => /^[a-zA-Z]{2,}$/.test(value), 
        'Name must contain only Latin letters and be at least 2 characters.');
    isValid &= validateField('lastname', value => /^[a-zA-Z]{2,}$/.test(value), 
        'Last name must contain only Latin letters and be at least 2 characters.');
    isValid &= validateField('email', value => /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value), 
        'Invalid email format.');
    isValid &= validateField('phone_number', value => /^\d{10,15}$/.test(value), 
        'Phone number must contain only digits and be between 10 and 15 characters.');
    isValid &= validateField('login', value => /^[a-zA-Z0-9_]{3,}$/.test(value), 
        'Login must contain only Latin letters, numbers, and underscores, and be at least 3 characters.');
    isValid &= validateField('password', value => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(value), 
        'Password must be at least 8 characters long, with uppercase, lowercase, and a number.');
    isValid &= validateField('gender', value => value !== '', 
        'You must select a gender.');

    // Если все поля валидны, можно отправлять форму
    if (isValid) {
        alert('Form submitted successfully!');
        // Здесь можно отправить данные через AJAX или обычным POST-запросом
        this.submit();
    }
});

// Валидация в реальном времени
document.getElementById('username').addEventListener('input', () => {
    validateField('username', value => /^[a-zA-Z]{2,}$/.test(value), 
        'Name must contain only Latin letters and be at least 2 characters.');
});
document.getElementById('lastname').addEventListener('input', () => {
    validateField('lastname', value => /^[a-zA-Z]{2,}$/.test(value), 
        'Last name must contain only Latin letters and be at least 2 characters.');
});
document.getElementById('email').addEventListener('input', () => {
    validateField('email', value => /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value), 
        'Invalid email format.');
});
document.getElementById('phone_number').addEventListener('input', () => {
    validateField('phone_number', value => /^\d{10,15}$/.test(value), 
        'Phone number must contain only digits and be between 10 and 15 characters.');
});
document.getElementById('login').addEventListener('input', () => {
    validateField('login', value => /^[a-zA-Z0-9_]{3,}$/.test(value), 
        'Login must contain only Latin letters, numbers, and underscores, and be at least 3 characters.');
});
document.getElementById('password').addEventListener('input', () => {
    validateField('password', value => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(value), 
        'Password must be at least 8 characters long, with uppercase, lowercase, and a number.');
});

document.getElementById('phone_number').addEventListener('input', () => {
    validateField(
        'phone_number',
        value => /^\+\d{1,3}\d{9,12}$/.test(value),
        'Phone number must start with a country code (e.g., +1) and have a maximum of 12 digits.'
    );
});
