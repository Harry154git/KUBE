<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\UserModel;
use App\Models\ProductModel; // Pastikan Anda punya ProductModel

class ChatController extends BaseController
{
    protected $conversationModel;
    protected $messageModel;
    protected $userModel;
    protected $productModel;

    public function __construct()
    {
        $this->conversationModel = new ConversationModel();
        $this->messageModel = new MessageModel();
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel(); // Inisialisasi ProductModel
    }

    public function index()
    {
        if (!$this->request->isAJAX()) { return $this->response->setStatusCode(403); }
        $userId = session()->get('user_id');
        $conversations = $this->conversationModel->getConversationsForUser($userId);
        return $this->response->setJSON($conversations);
    }

    public function startChatFromProduct($sellerId, $productId)
    {
        // Pastikan ini adalah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Invalid request type.');
        }

        $buyerId = session()->get('user_id');

        if (!$buyerId) {
            return $this->response->setJSON(['success' => false, 'error' => 'Silakan login untuk memulai chat.'])->setStatusCode(401);
        }
        if ($buyerId == $sellerId) {
            return $this->response->setJSON(['success' => false, 'error' => 'Anda tidak bisa mengirim pesan ke diri sendiri.'])->setStatusCode(400);
        }

        // Cari atau buat percakapan
        $conversation = $this->conversationModel->findConversation($buyerId, $sellerId);
        
        $conversationId = $conversation ? $conversation['id'] : $this->conversationModel->insert(['user1_id' => $buyerId, 'user2_id' => $sellerId]);

        // Cek apakah pesan produk ini sudah pernah dikirim
        $existingProductMessage = $this->messageModel->where([
            'conversation_id' => $conversationId, 'sender_id' => $buyerId, 'product_id' => $productId
        ])->first();
        
        // Hanya kirim pesan produk jika belum pernah ada di percakapan ini
        if (!$existingProductMessage) {
            $this->messageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id'       => $buyerId,
                'product_id'      => $productId,
                'message'         => null,
            ]);
        }
        
        $this->conversationModel->update($conversationId, ['updated_at' => date('Y-m-d H:i:s')]);

        // Kembalikan response JSON berisi ID percakapan
        return $this->response->setJSON(['success' => true, 'conversation_id' => $conversationId]);
    }
    
    public function getMessages($conversationId)
    {
        if (!$this->request->isAJAX()) { return $this->response->setStatusCode(403); }
        $userId = session()->get('user_id');
        $conversation = $this->conversationModel->find($conversationId);
        if (!$conversation || ($conversation['user1_id'] != $userId && $conversation['user2_id'] != $userId)) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403);
        }

        $this->messageModel->where('conversation_id', $conversationId)
            ->where('sender_id !=', $userId)->set(['is_read' => 1])->update();

        $messages = $this->messageModel
            ->select('messages.*, p.product_name, p.price, p.product_image')
            ->join('products p', 'p.id = messages.product_id', 'left')
            ->where('messages.conversation_id', $conversationId)
            ->orderBy('messages.created_at', 'ASC')
            ->findAll();
        
        $recipientId = ($conversation['user1_id'] == $userId) ? $conversation['user2_id'] : $conversation['user1_id'];
        $recipient = $this->userModel->select('id, full_name')->find($recipientId);

        return $this->response->setJSON(['messages' => $messages, 'recipient' => $recipient]);
    }

    public function sendMessage($conversationId)
    {
        if (!$this->request->isAJAX()) { return $this->response->setStatusCode(403); }
        $userId = session()->get('user_id');
        $messageText = $this->request->getPost('message');
        if (empty(trim($messageText))) { return $this->response->setJSON(['error' => 'Message cannot be empty.'])->setStatusCode(400); }
        $conversation = $this->conversationModel->find($conversationId);
        if (!$conversation || ($conversation['user1_id'] != $userId && $conversation['user2_id'] != $userId)) { return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403); }
        $this->messageModel->insert(['conversation_id' => $conversationId, 'sender_id' => $userId, 'message' => esc($messageText)]);
        $this->conversationModel->update($conversationId, ['updated_at' => date('Y-m-d H:i:s')]);
        return $this->response->setJSON(['success' => true, 'message' => 'Message sent.']);
    }

    /**
     * (DIPERBAIKI) Memulai chat dari penjual ke pembeli via AJAX, mengembalikan JSON.
     */
    public function startChatFromOrder($orderId)
    {
        // Pastikan ini adalah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Invalid request type.');
        }

        $sellerId = session()->get('user_id');
        if (!$sellerId) {
            return $this->response->setJSON(['success' => false, 'error' => 'Sesi tidak valid.'])->setStatusCode(401);
        }
        
        $orderModel = new \App\Models\OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order || $order['store_id'] != session()->get('store_id')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Pesanan tidak valid.'])->setStatusCode(403);
        }

        $buyerId = $order['user_id'];
        
        $conversation = $this->conversationModel->findConversation($buyerId, $sellerId);
        $conversationId = $conversation ? $conversation['id'] : $this->conversationModel->insert(['user1_id' => $buyerId, 'user2_id' => $sellerId]);

        $contextMessage = "Halo, ini mengenai pesanan Anda dengan no. invoice: " . $order['invoice_number'];
        
        // Cek apakah pesan konteks sudah ada untuk menghindari duplikat
        $existingMessage = $this->messageModel->where(['conversation_id' => $conversationId, 'message' => $contextMessage])->first();
        if (!$existingMessage) {
            $this->messageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id'       => $sellerId,
                'message'         => $contextMessage,
            ]);
            $this->conversationModel->update($conversationId, ['updated_at' => date('Y-m-d H:i:s')]);
        }
        
        // Kembalikan response JSON berisi ID percakapan
        return $this->response->setJSON(['success' => true, 'conversation_id' => $conversationId]);
    }
}