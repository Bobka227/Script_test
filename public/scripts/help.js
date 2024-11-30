document.addEventListener("DOMContentLoaded", function () {
    const openAnswerButtons = document.querySelectorAll('.open-answer-btn');

    openAnswerButtons.forEach(button => {
        button.addEventListener('click', () => {
            const questionItem = button.closest('.li-questions');
            const answerText = questionItem.querySelector('.q-answer-text');
            answerText.classList.toggle('d-none');
        });
    });
});
