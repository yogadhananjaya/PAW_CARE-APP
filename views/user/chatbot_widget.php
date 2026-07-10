<?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'User'): ?>
<!-- Floating AI Chatbot Widget -->
<style>
    /* Chatbot FAB Button */
    .chatbot-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 64px;
        height: 64px;
        background: transparent;
        border-radius: 50%;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 9999;
        padding: 0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .chatbot-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 24px rgba(0,0,0,0.3);
    }
    .chatbot-fab img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }
    
    /* Chatbot Window Container */
    .chatbot-window {
        position: fixed;
        bottom: 105px;
        right: 30px;
        width: 370px;
        height: 500px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 9999;
        transform: translateY(30px) scale(0.95);
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.1);
    }
    .chatbot-window.active {
        transform: translateY(0) scale(1);
        opacity: 1;
        pointer-events: auto;
    }
    
    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #e67e22, #f39c12);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chatbot-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .chatbot-avatar {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 2px solid white;
    }
    .chatbot-info-text h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }
    .chatbot-status {
        font-size: 11px;
        color: #ffe0b2;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .chatbot-status::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #2ecc71;
        border-radius: 50%;
    }
    .chatbot-close {
        cursor: pointer;
        color: white;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    .chatbot-close:hover {
        opacity: 1;
    }

    /* Message Area */
    .chatbot-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chat-bubble {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 13px;
        line-height: 1.5;
        word-wrap: break-word;
        font-family: 'Poppins', sans-serif;
    }
    .chat-bubble.bot {
        background: #ffffff;
        color: #334155;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .chat-bubble.user {
        background: #e67e22;
        color: #ffffff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    
    /* Typing Indicator */
    .chat-bubble.typing {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 12px 14px;
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typingBounce 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typingBounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* Footer Input */
    .chatbot-footer {
        padding: 12px 15px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .chatbot-input {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: 25px;
        padding: 8px 16px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
        font-family: 'Poppins', sans-serif;
    }
    .chatbot-input:focus {
        border-color: #e67e22;
    }
    .chatbot-send {
        background: #e67e22;
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }
    .chatbot-send:hover {
        background: #d35400;
    }
    .chatbot-send svg {
        width: 16px;
        height: 16px;
        fill: white;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
        .chatbot-window {
            width: calc(100% - 30px);
            right: 15px;
            left: 15px;
            bottom: 85px;
            height: 430px;
        }
        .chatbot-fab {
            right: 15px;
            bottom: 15px;
        }
    }
</style>

<!-- Toggle FAB Button -->
<div class="chatbot-fab" id="chatbotFab" onclick="toggleChatWindow()">
    <img src="assets/img/iconpawcare.png" alt="PawBot">
</div>

<!-- Chatbot Window -->
<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <div class="chatbot-profile">
            <div class="chatbot-avatar"><img src="assets/img/iconpawcare.png" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" alt="PawBot"></div>
            <div class="chatbot-info-text">
                <h4>PawBot</h4>
                <div class="chatbot-status">Online</div>
            </div>
        </div>
        <div class="chatbot-close" onclick="toggleChatWindow()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
    </div>
    
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="chat-bubble bot">
            Halo! Saya PawBot 🐾 Asisten adopsi AI dari PawCare. Ada yang bisa saya bantu untuk memilih sahabat bulu terbaik Anda?
        </div>
    </div>
    
    <div class="chatbot-footer">
        <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Tanyakan rekomendasi hewan..." onkeydown="handleChatKeydown(event)">
        <button class="chatbot-send" onclick="sendChatbotMessage()">
            <svg viewBox="0 0 24 24">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
            </svg>
        </button>
    </div>
</div>

<script>
    // Load chat history from sessionStorage on load
    document.addEventListener('DOMContentLoaded', () => {
        const savedHistory = sessionStorage.getItem('pawbot_chat_history');
        if (savedHistory) {
            document.getElementById('chatbotMessages').innerHTML = savedHistory;
            document.getElementById('chatbotMessages').scrollTop = document.getElementById('chatbotMessages').scrollHeight;
        }
        
        const isWindowActive = sessionStorage.getItem('pawbot_chat_active');
        if (isWindowActive === 'true') {
            document.getElementById('chatbotWindow').classList.add('active');
        }
    });

    function toggleChatWindow() {
        const windowEl = document.getElementById('chatbotWindow');
        windowEl.classList.toggle('active');
        sessionStorage.setItem('pawbot_chat_active', windowEl.classList.contains('active'));
        if (windowEl.classList.contains('active')) {
            document.getElementById('chatbotInput').focus();
        }
    }
    
    function handleChatKeydown(event) {
        if (event.key === 'Enter') {
            sendChatbotMessage();
        }
    }
    
    function saveChatHistory() {
        const messagesEl = document.getElementById('chatbotMessages');
        sessionStorage.setItem('pawbot_chat_history', messagesEl.innerHTML);
    }
    
    function sendChatbotMessage() {
        const inputEl = document.getElementById('chatbotInput');
        const message = inputEl.value.trim();
        if (!message) return;
        
        // Append user message
        appendMessage(message, 'user');
        inputEl.value = '';
        
        // Show typing indicator
        const typingId = showTypingIndicator();
        
        // Send AJAX Request
        const formData = new FormData();
        formData.append('message', message);
        
        fetch('index.php?page=chatbot_api', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            removeTypingIndicator(typingId);
            appendMessage(data.reply || 'Maaf, terjadi masalah respons.', 'bot');
        })
        .catch(err => {
            removeTypingIndicator(typingId);
            appendMessage('Maaf, gagal menghubungi server AI.', 'bot');
            console.error(err);
        });
    }
    
    function appendMessage(text, sender) {
        const messagesEl = document.getElementById('chatbotMessages');
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${sender}`;
        // Convert linebreaks to HTML tags for better AI formatting
        bubble.innerHTML = text.replace(/\n/g, '<br>');
        messagesEl.appendChild(bubble);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        saveChatHistory();
    }
    
    let typingCounter = 0;
    function showTypingIndicator() {
        const messagesEl = document.getElementById('chatbotMessages');
        const indicator = document.createElement('div');
        typingCounter++;
        const id = `typing-${typingCounter}`;
        indicator.id = id;
        indicator.className = 'chat-bubble bot typing';
        indicator.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
        messagesEl.appendChild(indicator);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        return id;
    }
    
    function removeTypingIndicator(id) {
        const indicator = document.getElementById(id);
        if (indicator) {
            indicator.remove();
        }
    }
</script>
<?php endif; ?>
