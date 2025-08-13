<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Added for consistency
    protected $useSoftDeletes   = false; // Added for clarity
    
    // Updated allowedFields to include toko_id
    protected $allowedFields    = ['toko_id', 'nama_produk', 'deskripsi', 'harga', 'stok', 'gambar_produk'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
