<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'My E-Commerce') ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background-color: #f8f9fa; }
        .main-content { min-height: 80vh; }
        .footer { background-color: #343a40; color: white; }

        /* ===== STYLE FOR CHAT FEATURE ===== */
        .chat-container { display: none; position: fixed; bottom: 20px; right: 20px; width: 700px; max-width: 90vw; height: 500px; max-height: 80vh; background-color: white; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); z-index: 1050; overflow: hidden; flex-direction: column; }
        .chat-container.open { display: flex; }
        .chat-header { background-color: #343a40; color: white; padding: 10px 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .chat-body { display: flex; flex-grow: 1; overflow: hidden; }
        .conversations-list { width: 35%; border-right: 1px solid #dee2e6; overflow-y: auto; padding: 0; margin: 0; list-style: none; }
        .conversation-item { padding: 15px; cursor: pointer; border-bottom: 1px solid #f1f1f1; display: flex; justify-content: space-between; align-items: center; }
        .conversation-item:hover { background-color: #f8f9fa; }
        .conversation-item.active { background-color: #e9ecef; }
        .conversation-item .name { font-weight: bold; }
        .conversation-item .last-message { font-size: 0.85em; color: #6c757d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
        .unread-badge { background-color: #dc3545; color: white; font-size: 0.7em; padding: 2px 6px; border-radius: 50%; }
        .message-window { width: 65%; display: flex; flex-direction: column; }
        .message-header { padding: 10px 15px; border-bottom: 1px solid #dee2e6; font-weight: bold; }
        .message-area { flex-grow: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; }
        .message { max-width: 70%; padding: 8px 12px; border-radius: 18px; margin-bottom: 10px; word-wrap: break-word; }
        .message.sent { background-color: #0d6efd; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
        .message.received { background-color: #e9ecef; color: #212529; align-self: flex-start; border-bottom-left-radius: 4px; }
        .message-input-area { border-top: 1px solid #dee2e6; padding: 10px; }
        .floating-chat-btn { position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background-color: #0d6efd; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 28px; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 1040; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?= $this->include('layouts/partials/navbar') ?>

    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->include('layouts/partials/footer') ?>

    <!-- ==================================================== -->
    <!-- ===== NEW SECTION: CHAT FEATURE ===== -->
    <!-- ==================================================== -->
    <div class="floating-chat-btn" id="toggleChatBtn"><i class="bi bi-chat-dots-fill"></i></div>

    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <span>Messages</span>
            <button type="button" class="btn-close btn-close-white" id="closeChatBtn" aria-label="Close"></button>
        </div>
        <div class="chat-body">
            <ul class="conversations-list" id="conversationsList">
                <div class="text-center p-5 spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
            </ul>
            <div class="message-window" id="messageWindow" style="display: none;">
                <div class="message-header" id="messageHeader"></div>
                <div class="message-area" id="messageArea"></div>
                <div class="message-input-area">
                    <form id="messageForm">
                        <div class="input-group">
                            <input type="text" id="messageInput" class="form-control" placeholder="Type a message..." autocomplete="off">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-send-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="noConversationSelected" class="d-flex w-100 justify-content-center align-items-center text-muted">
                <p>Select a conversation to start.</p>
            </div>
        </div>
    </div>
    <!-- ===== END OF CHAT FEATURE ===== -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>

    <!-- ===== SCRIPT FOR CHAT FEATURE ===== -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatContainer');
        const toggleChatBtn = document.getElementById('toggleChatBtn');
        const closeChatBtn = document.getElementById('closeChatBtn');
        const conversationsList = document.getElementById('conversationsList');
        const messageWindow = document.getElementById('messageWindow');
        const messageHeader = document.getElementById('messageHeader');
        const messageArea = document.getElementById('messageArea');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const noConversationSelected = document.getElementById('noConversationSelected');

        let currentConversationId = null;
        let messagePollingInterval = null;
        const currentUserId = <?= session()->get('user_id') ?? 'null' ?>;

        if (!currentUserId) return; // Do not run chat if the user is not logged in

        toggleChatBtn.addEventListener('click', () => {
            chatContainer.classList.toggle('open');
            if (chatContainer.classList.contains('open')) {
                loadConversations();
            }
        });

        closeChatBtn.addEventListener('click', () => chatContainer.classList.remove('open'));

        async function loadConversations() {
            conversationsList.innerHTML = `<div class="text-center p-5 spinner-border" role="status"></div>`;
            try {
                const response = await fetch('/chat/conversations', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const conversations = await response.json();
                renderConversations(conversations);
            } catch (error) {
                conversationsList.innerHTML = `<div class="text-center p-3 text-danger">Failed to load.</div>`;
            }
        }

        function renderConversations(conversations) {
            conversationsList.innerHTML = '';
            if (conversations.length === 0) {
                conversationsList.innerHTML = `<div class="text-center p-3 text-muted">No conversations.</div>`;
                return;
            }
            conversations.forEach(conv => {
                const item = document.createElement('li');
                item.className = 'conversation-item';
                item.dataset.conversationId = conv.conversation_id;
                item.innerHTML = `<div><div class="name">${escapeHTML(conv.recipient_name)}</div><div class="last-message">${conv.last_message ? escapeHTML(conv.last_message) : '...'}</div></div> ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}`;
                item.addEventListener('click', () => openConversation(conv.conversation_id));
                conversationsList.appendChild(item);
            });
        }

        async function openConversation(conversationId) {
            currentConversationId = conversationId;
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.toggle('active', item.dataset.conversationId == conversationId);
            });
            messageWindow.style.display = 'flex';
            noConversationSelected.style.display = 'none';
            messageArea.innerHTML = `<div class="text-center p-5 spinner-border" role="status"></div>`;
            if (messagePollingInterval) clearInterval(messagePollingInterval);

            try {
                const response = await fetch(`/chat/messages/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await response.json();
                messageHeader.textContent = escapeHTML(data.recipient.full_name); // Mengubah 'nama_lengkap'
                renderMessages(data.messages);
                messagePollingInterval = setInterval(() => loadNewMessages(conversationId), 5000);
            } catch (error) {
                messageArea.innerHTML = `<div class="text-center p-3 text-danger">Failed to load messages.</div>`;
            }
        }

        function renderMessages(messages) {
            messageArea.innerHTML = '';
            messages.forEach(msg => {
                const msgDiv = document.createElement('div');
                msgDiv.className = `message ${msg.sender_id == currentUserId ? 'sent' : 'received'}`;
                msgDiv.textContent = msg.message;
                messageArea.appendChild(msgDiv);
            });
            messageArea.scrollTop = messageArea.scrollHeight;
        }
        
        async function loadNewMessages(conversationId) {
            if (currentConversationId !== conversationId) return;
            try {
                const response = await fetch(`/chat/messages/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await response.json();
                renderMessages(data.messages);
            } catch (error) { console.error('Polling error:', error); }
        }

        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const messageText = messageInput.value.trim();
            if (!messageText || !currentConversationId) return;

            const formData = new FormData();
            formData.append('message', messageText);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            messageInput.value = '';
            const tempMsgDiv = document.createElement('div');
            tempMsgDiv.className = 'message sent';
            tempMsgDiv.textContent = messageText;
            messageArea.appendChild(tempMsgDiv);
            messageArea.scrollTop = messageArea.scrollHeight;

            try {
                const response = await fetch(`/chat/send/${currentConversationId}`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData });
                if (response.ok) loadConversations();
            } catch (error) { console.error('Send error:', error); }
        });

        function handleHashChange() {
            const hash = window.location.hash;
            if (hash.startsWith('#chat/conversation/')) {
                const conversationId = hash.split('/')[2];
                if (conversationId) {
                    chatContainer.classList.add('open');
                    loadConversations().then(() => openConversation(conversationId));
                }
            }
        }

        window.addEventListener('hashchange', handleHashChange);
        handleHashChange(); // Check when the page first loads

        function escapeHTML(str) {
            const p = document.createElement('p');
            p.appendChild(document.createTextNode(str));
            return p.innerHTML;
        }
    });
    </script>
</body>
</html>
