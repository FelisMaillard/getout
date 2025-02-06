const Messages = {
    init() {
        this.elements = {
            form: document.getElementById('message-form'),
            input: document.getElementById('message-input'),
            fileInput: document.getElementById('file-upload'),
            sendButton: document.getElementById('send-message'),
            messagesContainer: document.getElementById('messages-container'),
            messageEnd: document.getElementById('message-end')
        };

        this.isSubmitting = false;
        this.bindEvents();
        this.scrollToBottom();
    },

    bindEvents() {
        this.elements.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (this.isSubmitting) return;

            const content = this.elements.input.value.trim();
            if (content) {
                await this.sendMessage({ content, type: 'text' });
            }
        });

        this.elements.fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file || this.isSubmitting) return;

            if (this.validateFile(file)) {
                await this.sendFile(file);
            }
            e.target.value = '';
        });

        this.elements.input.addEventListener('input', () => {
            this.elements.sendButton.disabled = !this.elements.input.value.trim();
        });
    },

    async sendMessage(data) {
        if (this.isSubmitting) return;

        try {
            this.isSubmitting = true;
            this.setLoading(true);

            const formData = new FormData();
            formData.append('content', data.content);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const response = await fetch(this.elements.form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            });

            const result = await response.json();
            if (!response.ok) throw new Error(result.error || 'Erreur lors de l\'envoi');

            if (result.message) {
                this.appendMessage(result.message);
                this.elements.form.reset();
            }
        } catch (error) {
            this.showNotification(error.message, 'error');
            console.error(error);
        } finally {
            this.isSubmitting = false;
            this.setLoading(false);
        }
    },

    async sendFile(file) {
        if (this.isSubmitting) return;

        try {
            this.isSubmitting = true;
            this.setLoading(true);
            this.showNotification('Upload en cours...', 'info');

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const response = await fetch(this.elements.form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            });

            const result = await response.json();
            if (!response.ok) throw new Error(result.error || 'Erreur lors de l\'upload');

            if (result.message) {
                this.appendMessage(result.message);
                this.showNotification('Fichier envoyé avec succès', 'success');
            }
        } catch (error) {
            this.showNotification(error.message, 'error');
            console.error(error);
        } finally {
            this.isSubmitting = false;
            this.setLoading(false);
        }
    },

    appendMessage(messageHtml) {
        if (!this.elements.messagesContainer) return;

        const existingMessages = Array.from(document.querySelectorAll('#messages-container .message'));
        if (existingMessages.some(msg => msg.innerHTML === messageHtml)) return;

        this.elements.messageEnd.insertAdjacentHTML('beforebegin', messageHtml);
        this.scrollToBottom();
    },

    scrollToBottom() {
        if (this.elements.messagesContainer) {
            this.elements.messagesContainer.scrollTop = this.elements.messagesContainer.scrollHeight;
        }
    },

    setLoading(isLoading) {
        this.elements.sendButton.disabled = isLoading;
        this.elements.input.disabled = isLoading;
        this.elements.fileInput.disabled = isLoading;
    },

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-y-0 opacity-100 ${
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'success' ? 'bg-green-500 text-white' :
            'bg-purple-600 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = 'translateY(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

document.addEventListener('DOMContentLoaded', () => Messages.init());
