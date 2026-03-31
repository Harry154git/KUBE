<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'KUBE Purun') ?></title>
    <meta name="description" content="<?= $description ?? 'Kerajinan anyaman purun khas Kalimantan Selatan.' ?>">
    
    <!-- Hapus link Bootstrap jika Anda ingin mengadopsi penuh gaya baru -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <!-- (BARU) Wrapper utama untuk seluruh halaman -->
    <div class="page-wrapper">

        <?= $this->include('layouts/partials/navbar') ?>

        <main class="main-content">
            <?= $this->renderSection('content') ?>
        </main>

        <?= $this->include('layouts/partials/footer') ?>

    </div> <!-- Penutup .page-wrapper -->

    <!-- Modal dan Chat Widget tetap di sini jika masih diperlukan -->
    <?php if (!session()->get('isLoggedIn')): ?>
        <?= $this->include('layouts/partials/auth_modal') ?>
    <?php endif; ?>

    <?php if (session()->get('isLoggedIn')): ?>
        <!-- Kode Chat Widget Anda -->
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- (DIUBAH) Script utama untuk menangani semua logika dinamis -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;

        // --- LOGIKA UNTUK AUTH MODAL (Hanya berjalan jika belum login) ---
        if (!isLoggedIn) {
            const authModal = document.getElementById('authModal');
            if (authModal) {
                const authModalCloseBtn = document.getElementById('authModalCloseBtn');
                const loginFormContainer = document.getElementById('loginFormContainer');
                const registerFormContainer = document.getElementById('registerFormContainer');
                const showRegisterLink = document.getElementById('showRegister');
                const showLoginLink = document.getElementById('showLogin');
                const redirectUrlLogin = document.getElementById('redirectUrlLogin');
                const redirectUrlRegister = document.getElementById('redirectUrlRegister');

                const showAuthModal = (redirectUrl = window.location.href, showRegister = false) => {
                    redirectUrlLogin.value = redirectUrl;
                    redirectUrlRegister.value = redirectUrl;
                    if (showRegister) {
                        loginFormContainer.classList.remove('active');
                        registerFormContainer.classList.add('active');
                    } else {
                        registerFormContainer.classList.remove('active');
                        loginFormContainer.classList.add('active');
                    }
                    authModal.classList.add('show');
                };

                // Pemicu utama untuk menampilkan modal
                document.querySelectorAll('.requires-auth').forEach(el => {
                    el.addEventListener('click', function(e) {
                        e.preventDefault(); // Mencegah link/form berjalan
                        const redirectUrl = this.href || this.closest('form')?.action || window.location.href;
                        showAuthModal(redirectUrl);
                    });
                });
                
                // Logika untuk menutup modal
                authModalCloseBtn.addEventListener('click', () => authModal.classList.remove('show'));
                authModal.addEventListener('click', e => {
                    if (e.target === authModal) { // Hanya tutup jika klik background
                        authModal.classList.remove('show');
                    }
                });

                // Logika untuk berganti antara form login dan register
                showRegisterLink.addEventListener('click', () => {
                    loginFormContainer.classList.remove('active');
                    registerFormContainer.classList.add('active');
                });
                showLoginLink.addEventListener('click', () => {
                    registerFormContainer.classList.remove('active');
                    loginFormContainer.classList.add('active');
                });

                // Cek flash data untuk membuka modal secara otomatis jika ada error validasi
                <?php if (session()->getFlashdata('error') || session()->getFlashdata('validation')): ?>
                    const redirect = '<?= old('redirect_url', session()->getFlashdata('redirect_url') ?? '/home') ?>';
                    const showRegister = <?= session()->getFlashdata('validation') ? 'true' : 'false' ?>;
                    showAuthModal(redirect, showRegister);
                <?php endif; ?>
            }
        }

        // --- LOGIKA UNTUK CHAT WIDGET (Hanya berjalan jika sudah login) ---
        if (isLoggedIn) {
            const toggleChatBtn = document.getElementById('toggleChatBtn');
            if (toggleChatBtn) {
                const navbarChatIcon = document.getElementById('navbarChatIcon');
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

                function escapeHTML(str) { const p = document.createElement('p'); p.appendChild(document.createTextNode(str || "")); return p.innerHTML; }
                async function loadConversations() { conversationsList.innerHTML = `<div class="text-center p-5 spinner-border" role="status"></div>`; try { const response = await fetch('/chat/conversations', { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); const conversations = await response.json(); renderConversations(conversations); } catch (error) { conversationsList.innerHTML = `<div class="text-center p-3 text-danger">Gagal memuat.</div>`; } }
                function renderConversations(conversations) { conversationsList.innerHTML = ''; if (conversations.length === 0) { conversationsList.innerHTML = `<div class="text-center p-3 text-muted">Tidak ada percakapan.</div>`; return; } conversations.forEach(conv => { const item = document.createElement('li'); item.className = 'conversation-item'; item.dataset.conversationId = conv.conversation_id; item.innerHTML = `<div><div class="name">${escapeHTML(conv.recipient_name)}</div><div class="last-message">${conv.last_message ? escapeHTML(conv.last_message) : '...'}</div></div> ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}`; item.addEventListener('click', () => openConversation(conv.conversation_id)); conversationsList.appendChild(item); }); }
                async function openConversation(conversationId) { currentConversationId = conversationId; document.querySelectorAll('.conversation-item').forEach(item => { item.classList.toggle('active', item.dataset.conversationId == conversationId); }); messageWindow.style.display = 'flex'; noConversationSelected.classList.add('d-none'); noConversationSelected.classList.remove('d-flex'); messageArea.innerHTML = `<div class="text-center p-5 spinner-border" role="status"></div>`; if (messagePollingInterval) clearInterval(messagePollingInterval); try { const response = await fetch(`/chat/messages/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); const data = await response.json(); messageHeader.textContent = escapeHTML(data.recipient.full_name); renderMessages(data.messages); messagePollingInterval = setInterval(() => loadNewMessages(conversationId), 5000); } catch (error) { messageArea.innerHTML = `<div class="text-center p-3 text-danger">Gagal memuat pesan.</div>`; } }
                function renderMessages(messages) { messageArea.innerHTML = ''; messages.forEach(msg => { const msgDiv = document.createElement('div'); if (msg.product_id && msg.product_name) { msgDiv.className = `message product-inquiry ${msg.sender_id == currentUserId ? 'sent' : 'received'}`; const productUrl = `/product/${msg.product_id}`; const imageUrl = `/uploads/products/${msg.product_image}`; msgDiv.innerHTML = `<a href="${productUrl}" target="_blank" class="product-inquiry-content"><img src="${imageUrl}" class="product-inquiry-img" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';"><div class="product-inquiry-details"><p class="name">${escapeHTML(msg.product_name)}</p><p class="price">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(msg.price)}</p></div></a>`; } else { msgDiv.className = `message ${msg.sender_id == currentUserId ? 'sent' : 'received'}`; msgDiv.textContent = msg.message; } messageArea.appendChild(msgDiv); }); messageArea.scrollTop = messageArea.scrollHeight; }
                async function loadNewMessages(conversationId) { if (currentConversationId !== conversationId) return; try { const response = await fetch(`/chat/messages/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); const data = await response.json(); renderMessages(data.messages); } catch (error) { console.error('Polling error:', error); } }
                
                window.KUBE_CHAT = {
                    open: () => { chatContainer.classList.add('open'); loadConversations(); },
                    close: () => chatContainer.classList.remove('open'),
                    toggle: () => toggleChatBtn.click(),
                    openToConversation: (id) => {
                        if (!chatContainer.classList.contains('open')) {
                            chatContainer.classList.add('open');
                            loadConversations().then(() => openConversation(id));
                        } else { openConversation(id); }
                    }
                };
                
                if (navbarChatIcon) { navbarChatIcon.addEventListener('click', (e) => { e.preventDefault(); window.KUBE_CHAT.toggle(); }); }
                toggleChatBtn.addEventListener('click', () => { chatContainer.classList.toggle('open'); if (chatContainer.classList.contains('open')) { loadConversations(); } });
                closeChatBtn.addEventListener('click', () => { chatContainer.classList.remove('open'); messageWindow.style.display = 'none'; noConversationSelected.classList.remove('d-none'); noConversationSelected.classList.add('d-flex'); });
                messageForm.addEventListener('submit', async (e) => { e.preventDefault(); const messageText = messageInput.value.trim(); if (!messageText || !currentConversationId) return; const formData = new FormData(); formData.append('message', messageText); formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); messageInput.value = ''; const tempMsgDiv = document.createElement('div'); tempMsgDiv.className = 'message sent'; tempMsgDiv.textContent = messageText; messageArea.appendChild(tempMsgDiv); messageArea.scrollTop = messageArea.scrollHeight; try { const response = await fetch(`/chat/send/${currentConversationId}`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData }); if (response.ok) { loadNewMessages(currentConversationId); loadConversations(); } } catch (error) { console.error('Send error:', error); } });

                function handleHashChange() { const hash = window.location.hash; if (hash.startsWith('#conversation/')) { const conversationId = hash.split('/')[2]; if (conversationId) { window.KUBE_CHAT.openToConversation(conversationId); } } }
                window.addEventListener('hashchange', handleHashChange);
                handleHashChange();
            }
        }
    });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>