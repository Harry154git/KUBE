<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\UserModel;
use App\Models\StoreModel; // Mengubah dari TokoModel menjadi StoreModel

class ChatController extends BaseController
{
    protected $conversationModel;
    protected $messageModel;
    protected $userModel;
    protected $storeModel; // Mengubah dari $tokoModel menjadi $storeModel

    public function __construct()
    {
        $this->conversationModel = new ConversationModel();
        $this->messageModel = new MessageModel();
        $this->userModel = new UserModel();
        $this->storeModel = new StoreModel(); // Mengubah dari TokoModel()
    }

    /**
     * JSON endpoint to retrieve a user's conversation list.
     */
    public function index()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $userId = session()->get('user_id');
        $conversations = $this->conversationModel->getConversationsForUser($userId);

        return $this->response->setJSON($conversations);
    }

    /**
     * Starts a new conversation with a seller or shows an existing one.
     * This function finds the seller's user_id from the given store_id.
     */
    public function startWithSeller($storeId) // Mengubah 'tokoId' menjadi 'storeId'
    {
        $currentUserId = session()->get('user_id');
        
        $store = $this->storeModel->find($storeId); // Mengubah $toko menjadi $store
        if (!$store) {
            return redirect()->back()->with('error', 'Store not found.');
        }

        $sellerUserId = $store['user_id'];

        // Prevents a user from chatting with themselves
        if ($currentUserId == $sellerUserId) {
            return redirect()->back()->with('error', 'You cannot send messages to your own store.');
        }

        $conversation = $this->conversationModel->findConversation($currentUserId, $sellerUserId);

        if (!$conversation) {
            // Create a new conversation if it doesn't exist
            $conversationId = $this->conversationModel->insert([
                'user1_id' => $currentUserId,
                'user2_id' => $sellerUserId,
            ]);
        } else {
            $conversationId = $conversation['id'];
        }

        // Redirect to a URL that can automatically open the chat window
        return redirect()->to('/#chat/conversation/' . $conversationId);
    }

    /**
     * JSON endpoint to retrieve all messages within a conversation.
     */
    public function getMessages($conversationId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $userId = session()->get('user_id');

        // Security: ensure the user is part of this conversation
        $conversation = $this->conversationModel->find($conversationId);
        if (!$conversation || ($conversation['user1_id'] != $userId && $conversation['user2_id'] != $userId)) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403);
        }

        // Mark messages from the other party as "read"
        $this->messageModel
            ->where('conversation_id', $conversationId)
            ->where('sender_id !=', $userId)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();

        $messages = $this->messageModel
                             ->where('conversation_id', $conversationId)
                             ->orderBy('created_at', 'ASC')
                             ->findAll();
        
        $recipientId = ($conversation['user1_id'] == $userId) ? $conversation['user2_id'] : $conversation['user1_id'];
        $recipient = $this->userModel->select('id, full_name')->find($recipientId); // Mengubah 'nama_lengkap'

        return $this->response->setJSON([
            'messages' => $messages,
            'recipient' => $recipient
        ]);
    }

    /**
     * JSON endpoint to send a new message.
     */
    public function sendMessage($conversationId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $userId = session()->get('user_id');
        $messageText = $this->request->getPost('message');

        if (empty(trim($messageText))) {
            return $this->response->setJSON(['error' => 'Message cannot be empty.'])->setStatusCode(400);
        }

        $conversation = $this->conversationModel->find($conversationId);
        if (!$conversation || ($conversation['user1_id'] != $userId && $conversation['user2_id'] != $userId)) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403);
        }

        $this->messageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $userId,
            'message'         => esc($messageText), // Sanitize the message
        ]);
        
        // Update the conversation's timestamp to bring it to the top of the list
        $this->conversationModel->update($conversationId, ['updated_at' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON(['success' => true, 'message' => 'Message sent.']);
    }
}
