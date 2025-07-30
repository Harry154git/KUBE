<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_produk' => 'Laptop Gaming Pro',
                'deskripsi'    => 'Laptop dengan performa tinggi untuk gaming dan desain grafis.',
                'harga' => '15000000',
                'stok' => 25,
                'gambar_produk' => 'laptop_gaming.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_produk' => 'Mouse Wireless Ergonomis',
                'deskripsi'    => 'Mouse wireless yang nyaman digunakan berjam-jam.',
                'harga' => '250000',
                'stok' => 150,
                'gambar_produk' => 'mouse_wireless.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_produk' => 'Keyboard Mechanical RGB',
                'deskripsi'    => 'Keyboard mechanical dengan lampu RGB yang bisa dikustomisasi.',
                'harga' => '750000',
                'stok' => 70,
                'gambar_produk' => 'keyboard_mechanical.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('products')->insertBatch($data);
    }
}