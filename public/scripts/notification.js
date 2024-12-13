class Notification {
    constructor(containerId = 'notifications') {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = containerId;
            this.container.className = 'notifications';
            document.body.appendChild(this.container);
        }
    }

    show(message, sender = 'System', duration = 4000) {
        this.container.innerHTML = `<p><strong>${sender}:</strong> ${message}</p>`;
        this.container.classList.add('show');
        setTimeout(() => {
            this.container.classList.remove('show');
        }, duration);
    }

    async checkNewMessages(apiEndpoint = 'check_new_messages.php') {
        try {
            const response = await fetch(apiEndpoint);
            if (!response.ok) throw new Error('Failed to check new messages');

            const data = await response.json();
            if (data.new_messages > 0) {
                data.messages.forEach(msg => {
                    this.show(msg.text, msg.sender); // Отображение сообщения с именем отправителя
                });
            }
        } catch (error) {
            console.error('Error checking new messages:', error);
        }
    }
}

// Экспорт класса
export default Notification;
