<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
    protected $table          = 'conversations';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields    = ['user1_id', 'user2_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findConversation(int $user1Id, int $user2Id)
    {
        return $this->where("(user1_id = $user1Id AND user2_id = $user2Id)")
            ->orWhere("(user1_id = $user2Id AND user2_id = $user1Id)")
            ->first();
    }

    public function getConversationsForUser(int $userId)
    {
        $builder = $this->db->table('conversations c');
        $builder->select('
            c.id as conversation_id,
            c.updated_at,
            u.id as recipient_id,
            u.full_name as recipient_name,
            (SELECT message FROM messages m WHERE m.conversation_id = c.id ORDER BY m.created_at DESC LIMIT 1) as last_message,
            (SELECT COUNT(*) FROM messages m_unread WHERE m_unread.conversation_id = c.id AND m_unread.sender_id != '.$userId.' AND m_unread.is_read = 0) as unread_count
        ');
        $builder->join('users u', 'u.id = IF(c.user1_id = '.$userId.', c.user2_id, c.user1_id)');
        $builder->where('c.user1_id', $userId);
        $builder->orWhere('c.user2_id', $userId);
        $builder->orderBy('c.updated_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
