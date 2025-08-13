<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data pengguna contoh
        $users = [
            [
                'nama_lengkap' => 'Budi Sanjaya',
                'email'        => 'budi.sanjaya@example.com',
                'password'     => password_hash('password123', PASSWORD_DEFAULT),
                'is_seller'    => 1, // Penjual
                'toko_id'      => 1, // Akan dibuat di TokoSeeder
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lengkap' => 'Citra Lestari',
                'email'        => 'citra.lestari@example.com',
                'password'     => password_hash('password123', PASSWORD_DEFAULT),
                'is_seller'    => 1, // Penjual
                'toko_id'      => 2, // Akan dibuat di TokoSeeder
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lengkap' => 'Andi Pratama',
                'email'        => 'andi.pratama@example.com',
                'password'     => password_hash('password123', PASSWORD_DEFAULT),
                'is_seller'    => 0, // Pembeli biasa
                'toko_id'      => null,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        // Menggunakan Query Builder untuk insert data
        $this->db->table('users')->insertBatch($users);
    }
}
