// Создание элемента для уведомлений
const notifications = document.createElement('div');
notifications.id = 'notifications';
notifications.classList.add('notifications');
document.body.appendChild(notifications);

// Функция для показа уведомлений
function showNotification(message) {
    notifications.innerHTML = `<p>${message}</p>`;
    notifications.classList.add('show');
    setTimeout(() => notifications.classList.remove('show'), 4000);
}

// Функция проверки новых сообщений
async function checkNewMessages() {
    try {
        const response = await fetch('/pages/check_new_messages.php');
        if (!response.ok) throw new Error('Failed to check new messages');

        const data = await response.json();
        if (data.new_messages > 0) {
            showNotification(`You have ${data.new_messages} new message(s)`);
        }
    } catch (error) {
        console.error('Error checking new messages:', error);
    }
}

// Запуск проверки уведомлений с интервалом
setInterval(checkNewMessages, 10000); // Проверка каждые 10 секунд

// Начальная проверка уведомлений
checkNewMessages();
