<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table          = 'carts';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['user_id', 'product_id', 'quantity'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getCartItems(int $userId, ?int $cartId = null)
    {
        $builder = $this->select('
                carts.id as cart_id, 
                carts.quantity, 
                products.id as product_id, 
                products.product_name, 
                products.price,      
                products.product_image, 
                products.store_id    
            ')
            ->join('products', 'products.id = carts.product_id')
            ->where('carts.user_id', $userId);

        if ($cartId) {
            $builder->where('carts.id', $cartId);
            return $builder->first();
        }

        return $builder->findAll();
    }
}
