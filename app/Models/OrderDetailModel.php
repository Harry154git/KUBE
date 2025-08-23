<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table          = 'order_details';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields    = ['order_id', 'product_id', 'quantity', 'price_at_purchase'];

    public function getOrdersByStoreId(int $storeId)
    {
        return $this->select('
                order_details.*, 
                p.product_name as product_name,
                u.full_name as customer_name,
                o.invoice_number,
                o.status as order_status,
                o.created_at as order_date
            ')
            ->join('orders o', 'o.id = order_details.order_id')
            ->join('products p', 'p.id = order_details.product_id')
            ->join('users u', 'u.id = o.user_id')
            ->where('o.store_id', $storeId)
            ->orderBy('order_details.id', 'DESC')
            ->findAll();
    }
}
