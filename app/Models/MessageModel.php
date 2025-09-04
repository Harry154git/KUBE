<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // (DIPERBAIKI) Tambahkan 'product_id'
    protected $allowedFields = ['conversation_id', 'sender_id', 'message', 'product_id', 'is_read', 'read_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}