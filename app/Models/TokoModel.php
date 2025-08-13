<?php 

namespace App\Models;

use CodeIgniter\Model;

class TokoModel extends Model
{
    protected $table            = 'toko';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['user_id', 'nama_toko', 'deskripsi_toko', 'alamat_toko', 'rekening_bank'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTokoByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}