<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'KUBE Purun') ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

    <?= $this->include('layouts/partials/navbar') ?>

    <?php if (empty($isHomepage)): ?>
        <main class="main-content container my-4">
            <?= $this->renderSection('content') ?>
        </main>
    <?php else: ?>
        <?= $this->renderSection('content') ?>
    <?php endif; ?>

    <?= $this->include('layouts/partials/footer') ?>

    <div class="floating-chat-btn" id="toggleChatBtn">
        <i class="bi bi-chat-dots-fill"></i>
        <span class="chat-text">Chat</span>
    </div>

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
                 <p>Pilih percakapan untuk memulai.</p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // (DIUBAH) Seluruh script chat di-refactor agar lebih modular
    document.addEventListener('DOMContentLoaded', function() {
        const navbarChatIcon = document.getElementById('navbarChatIcon');
        const toggleChatBtn = document.getElementById('toggleChatBtn');
        const chatContainer = document.getElementById('chatContainer');
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

        if (!currentUserId) {
            toggleChatBtn.style.display = 'none';
            if (navbarChatIcon) navbarChatIcon.style.display = 'none';
            return;
        }

        async function loadConversations() { /* ... (fungsi ini tetap sama) ... */ }
        function renderConversations(conversations) { /* ... (fungsi ini tetap sama) ... */ }
        async function openConversation(conversationId) { /* ... (fungsi ini tetap sama) ... */ }
        function renderMessages(messages) { /* ... (fungsi ini tetap sama) ... */ }
        async function loadNewMessages(conversationId) { /* ... (fungsi ini tetap sama) ... */ }
        function escapeHTML(str) { /* ... (fungsi ini tetap sama) ... */ }

        // (BARU) Jadikan fungsi-fungsi penting ini bisa diakses secara global
        // Ini adalah kunci agar halaman lain bisa mengontrol widget chat
        window.KUBE_CHAT = {
            open: () => {
                chatContainer.classList.add('open');
                loadConversations();
            },
            close: () => chatContainer.classList.remove('open'),
            toggle: () => toggleChatBtn.click(),
            openToConversation: (id) => {
                if (!chatContainer.classList.contains('open')) {
                    chatContainer.classList.add('open');
                    loadConversations().then(() => openConversation(id));
                } else {
                    openConversation(id);
                }
            }
        };

        // Event Listeners
        if (navbarChatIcon) {
            navbarChatIcon.addEventListener('click', (e) => { e.preventDefault(); window.KUBE_CHAT.toggle(); });
        }
        toggleChatBtn.addEventListener('click', () => {
            chatContainer.classList.toggle('open');
            if (chatContainer.classList.contains('open')) { loadConversations(); }
        });
        closeChatBtn.addEventListener('click', () => {
            chatContainer.classList.remove('open');
            messageWindow.style.display = 'none';
            noConversationSelected.classList.remove('d-none');
            noConversationSelected.classList.add('d-flex');
        });
        messageForm.addEventListener('submit', async (e) => { /* ... (fungsi ini tetap sama) ... */ });

        // (DIUBAH) handleHashChange sekarang menggunakan fungsi global
        function handleHashChange() {
            const hash = window.location.hash;
            if (hash.startsWith('#conversation/')) {
                const conversationId = hash.split('/')[2];
                if (conversationId) {
                    window.KUBE_CHAT.openToConversation(conversationId);
                }
            }
        }
        window.addEventListener('hashchange', handleHashChange);
        handleHashChange();

        // --- Sisipkan kembali fungsi-fungsi yang tidak diubah ---
        async function loadConversations() { conversationsList.innerHTML = `<div class="text-center p-5 spinner-border" role="status"></div>`; try { const response = await fetch('/chat/conversations', { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); const conversations = await response.json(); renderConversations(conversations); } catch (error) { conversationsList.innerHTML = `<div class="text-center p-3 text-danger">Gagal memuat.</div>`; } }
        function renderConversations(conversations) { conversationsList.innerHTML = ''; if (conversations.length === 0) { conversationsList.innerHTML = `<div class="text-center p-3 text-muted">Tidak ada percakapan.</div>`; return; } conversations.forEach(conv => { const item = document.createElement('li'); item.className = 'conversation-item'; item.dataset.conversationId = conv.conversation_id; item.innerHTML = `<div><div class="name">${escapeHTML(conv.recipient_name)}</div><div class="last-message">${conv.last_message ? escapeHTML(conv.last_message) : '...'}</div></div> ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}`; item.addEventListener('click', () => openConversation(conv.conversation_id)); conversationsList.appendChild(item); }); }
        async function openConversation(conversationId) { currentConversationId = conversationId; document.querySelectorAll('.conversation-item').forEach(item => { item.classList.toggle('active', item.dataset.conversationId == conversationId); }); messageWindow.style.display = 'flex'; noConversationSelected.classList.add('d-none'); noConversationSelected.classList.remove('d-flex'); messageArea.innerHTML = `<div class="text-center p-5 spinner-border" role="status"></div>`; if (messagePollingInterval) clearInterval(messagePollingInterval); try { const response = await fetch(`/chat/messages/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); const data = await response.json(); messageHeader.textContent = escapeHTML(data.recipient.full_name); renderMessages(data.messages); messagePollingInterval = setInterval(() => loadNewMessages(conversationId), 5000); } catch (error) { messageArea.innerHTML = `<div class="text-center p-3 text-danger">Gagal memuat pesan.</div>`; } }
        function renderMessages(messages) { messageArea.innerHTML = ''; messages.forEach(msg => { const msgDiv = document.createElement('div'); if (msg.product_id && msg.product_name) { msgDiv.className = `message product-inquiry ${msg.sender_id == currentUserId ? 'sent' : 'received'}`; const productUrl = `/product/${msg.product_id}`; const imageUrl = `/uploads/products/${msg.product_image}`; msgDiv.innerHTML = `<a href="${productUrl}" target="_blank" class="product-inquiry-content"><img src="${imageUrl}" class="product-inquiry-img" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';"><div class="product-inquiry-details"><p class="name">${escapeHTML(msg.product_name)}</p><p class="price">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(msg.price)}</p></div></a>`; } else { msgDiv.className = `message ${msg.sender_id == currentUserId ? 'sent' : 'received'}`; msgDiv.textContent = msg.message; } messageArea.appendChild(msgDiv); }); messageArea.scrollTop = messageArea.scrollHeight; }
        async function loadNewMessages(conversationId) { if (currentConversationId !== conversationId) return; try { const response = await fetch(`/chat/messages/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); const data = await response.json(); renderMessages(data.messages); } catch (error) { console.error('Polling error:', error); } }
        messageForm.addEventListener('submit', async (e) => { e.preventDefault(); const messageText = messageInput.value.trim(); if (!messageText || !currentConversationId) return; const formData = new FormData(); formData.append('message', messageText); formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); messageInput.value = ''; const tempMsgDiv = document.createElement('div'); tempMsgDiv.className = 'message sent'; tempMsgDiv.textContent = messageText; messageArea.appendChild(tempMsgDiv); messageArea.scrollTop = messageArea.scrollHeight; try { const response = await fetch(`/chat/send/${currentConversationId}`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData }); if (response.ok) { loadNewMessages(currentConversationId); loadConversations(); } } catch (error) { console.error('Send error:', error); } });
        function escapeHTML(str) { const p = document.createElement('p'); p.appendChild(document.createTextNode(str || "")); return p.innerHTML; }
    });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>