<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Produk untuk Sanjaya Elektronik (toko_id = 1)
            [
                'toko_id'       => 1,
                'nama_produk'   => 'Smart TV 4K 50 inch',
                'deskripsi'     => 'Nikmati tayangan berkualitas tinggi dengan Smart TV resolusi 4K.',
                'harga'         => 5500000,
                'stok'          => 15,
                'gambar_produk' => 'smart_tv.jpg',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'toko_id'       => 1,
                'nama_produk'   => 'Wireless Mouse Gaming',
                'deskripsi'     => 'Mouse gaming tanpa kabel dengan DPI tinggi untuk presisi maksimal.',
                'harga'         => 350000,
                'stok'          => 40,
                'gambar_produk' => 'mouse_gaming.jpg',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            // Produk untuk Citra Fashion Store (toko_id = 2)
            [
                'toko_id'       => 2,
                'nama_produk'   => 'Blouse Wanita Katun',
                'deskripsi'     => 'Blouse elegan dari bahan katun premium, nyaman dipakai sehari-hari.',
                'harga'         => 180000,
                'stok'          => 50,
                'gambar_produk' => 'blouse_wanita.jpg',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'toko_id'       => 2,
                'nama_produk'   => 'Celana Jeans Pria Slim Fit',
                'deskripsi'     => 'Celana jeans model slim fit dengan bahan stretch yang nyaman.',
                'harga'         => 250000,
                'stok'          => 30,
                'gambar_produk' => 'jeans_pria.jpg',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($products);
    }
}
