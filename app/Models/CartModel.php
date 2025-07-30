<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table            = 'carts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['user_id', 'product_id', 'quantity'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Mengambil item keranjang milik user tertentu,
     * lengkap dengan detail produknya.
     */
    public function getCartItems(int $userId)
    {
        return $this->select('carts.id as cart_id, carts.quantity, products.id as product_id, products.nama_produk, products.harga, products.gambar_produk')
                    ->join('products', 'products.id = carts.product_id')
                    ->where('carts.user_id', $userId)
                    ->findAll();
    }
}