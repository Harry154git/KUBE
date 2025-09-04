<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table          = 'orders';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields  = [
        'invoice_number',
        'user_id',
        'store_id',
        'shipping_address_id',
        'total_amount',
        'shipping_cost',
        'status',
        'shipping_method',  // <-- TAMBAHKAN INI
        'payment_method',   // <-- TAMBAHKAN INI
        'seller_notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}