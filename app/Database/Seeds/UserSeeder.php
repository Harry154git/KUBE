<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama_lengkap' => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT), // Jangan gunakan password lemah di produksi!
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Simple Queries
        // $this->db->query('INSERT INTO users (nama_lengkap, email, password, created_at, updated_at) VALUES(:nama_lengkap:, :email:, :password:, :created_at:, :updated_at:)', $data);

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}