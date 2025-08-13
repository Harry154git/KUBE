<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['pesanan_id', 'produk_id', 'toko_id', 'jumlah', 'harga_saat_beli', 'status_pesanan_penjual'];

    public function getOrdersByTokoId($tokoId)
    {
        // Query ini mengambil detail pesanan dan menggabungkannya dengan informasi
        // produk dan pengguna (pembeli) untuk ditampilkan di halaman pesanan penjual.
        return $this->select('detail_pesanan.*, p.nama_produk as nama_produk, u.nama_lengkap as nama_pembeli, pesanan.created_at as tanggal_pesan')
            // --- PERBAIKAN DI SINI ---
            ->join('products p', 'p.id = detail_pesanan.produk_id') // Mengubah 'produk' menjadi 'products'
            // --- AKHIR PERBAIKAN ---
            ->join('pesanan', 'pesanan.id = detail_pesanan.pesanan_id')
            ->join('users u', 'u.id = pesanan.user_id')
            ->where('detail_pesanan.toko_id', $tokoId)
            ->orderBy('detail_pesanan.id', 'DESC')
            ->findAll();
    }
}
