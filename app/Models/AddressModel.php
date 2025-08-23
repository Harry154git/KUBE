<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table            = 'addresses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id', 'label', 'recipient_name', 'phone_number', 
        'address', 'city', 'province', 'postal_code', 'is_primary'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Menghitung jumlah alamat yang dimiliki oleh seorang user.
     * @param int $userId
     * @return int
     */
    public function countByUserId(int $userId): int
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

    /**
     * Mengatur sebuah alamat menjadi alamat utama.
     * Secara otomatis akan menonaktifkan status utama alamat lain milik user yang sama.
     * @param int $addressId
     * @param int $userId
     * @return bool
     */
    public function setPrimaryAddress(int $addressId, int $userId): bool
    {
        $this->db->transStart();

        // 1. Set semua alamat user menjadi TIDAK utama
        $this->where('user_id', $userId)->set(['is_primary' => 0])->update();

        // 2. Set alamat yang dipilih menjadi UTAMA
        $this->where('id', $addressId)->where('user_id', $userId)->set(['is_primary' => 1])->update();

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}