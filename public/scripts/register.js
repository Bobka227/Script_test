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
    function showTooltip(inputId, message) {
        const input = document.getElementById(inputId);

        const existingTooltip = input.parentNode.querySelector('.dynamic-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }

        if (!message) return;

        const tooltip = document.createElement('div');
        tooltip.className = 'dynamic-tooltip';
        tooltip.textContent = message;

        tooltip.style.position = 'absolute';
        tooltip.style.backgroundColor = '#f8d7da';
        tooltip.style.color = '#721c24';
        tooltip.style.padding = '5px';
        tooltip.style.borderRadius = '5px';
        tooltip.style.fontSize = '0.85rem';
        tooltip.style.boxShadow = '0px 2px 6px rgba(0, 0, 0, 0.15)';
        tooltip.style.zIndex = '1000';
        tooltip.style.whiteSpace = 'normal';
        tooltip.style.transform = 'translateY(-10px)';
        tooltip.style.opacity = '0';
        tooltip.style.transition = 'opacity 0.3s, transform 0.3s';

        input.parentNode.appendChild(tooltip);

        const inputRect = input.getBoundingClientRect();
        tooltip.style.left = `${input.offsetLeft + input.offsetWidth / 2 - tooltip.offsetWidth / 2}px`;
        tooltip.style.top = `${input.offsetTop - tooltip.offsetHeight - 10}px`;

        setTimeout(() => {
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateY(0)';
        }, 10);
    }

    function validateInput(inputId, regex, errorMessage) {
        const input = document.getElementById(inputId);
        const value = input.value.trim();

        if (value === '') {
            showTooltip(inputId, '');
            return;
        }

        const isValid = regex.test(value);
        if (!isValid) {
            showTooltip(inputId, errorMessage);
        } else {
            showTooltip(inputId, ''); 
        }
    }

  
    function removeTooltipOnBlur(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('blur', () => {
            showTooltip(inputId, ''); 
        });
    }

    document.getElementById('username').addEventListener('input', () => {
        validateInput(
            'username',
            /^[a-zA-Z]{2,}$/,
            'Name must contain only Latin letters and be at least 2 characters long.'
        );
    });
    removeTooltipOnBlur('username'); 

    document.getElementById('lastname').addEventListener('input', () => {
        validateInput(
            'lastname',
            /^[a-zA-Z]{2,}$/,
            'Last name must contain only Latin letters and be at least 2 characters long.'
        );
    });
    removeTooltipOnBlur('lastname'); 
    document.getElementById('login').addEventListener('input', () => {
        validateInput(
            'login',
            /^[a-zA-Z0-9_]{3,}$/,
            'Login must be in Latin letters, numbers, underscores, and at least 3 characters long.'
        );
    });
    removeTooltipOnBlur('login'); 


    function validatePhoneNumber(inputId) {
        const input = document.getElementById(inputId);
        const value = input.value.trim();

        const phoneRegex = /^\+\d{1,3}\d{9,12}$/;

        if (value === '') {
            showTooltip(inputId, 'Phone number is required.');
        } else if (!phoneRegex.test(value)) {
            showTooltip(
                inputId,
                'Phone number must start with a country code (e.g., +1) and have a maximum of 12 digits.'
            );
        } else {
            showTooltip(inputId, ''); 
        }
    }

    function removeTooltipOnBlur(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('blur', () => {
            showTooltip(inputId, ''); 
        });
    }

    document.getElementById('phone_number').addEventListener('input', () => {
        validatePhoneNumber('phone_number');
    });

    removeTooltipOnBlur('phone_number');
});



