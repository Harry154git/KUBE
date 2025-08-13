<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TokoSeeder extends Seeder
{
    public function run()
    {
        $toko = [
            [
                'id'              => 1, // Harus cocok dengan toko_id di UserSeeder
                'user_id'         => 1, // ID Budi Sanjaya
                'nama_toko'       => 'Sanjaya Elektronik',
                'deskripsi_toko'  => 'Menjual berbagai macam barang elektronik original dan bergaransi.',
                'alamat_toko'     => 'Jakarta Pusat',
                'rekening_bank'   => 'BCA 1122334455 a/n Budi Sanjaya',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'id'              => 2, // Harus cocok dengan toko_id di UserSeeder
                'user_id'         => 2, // ID Citra Lestari
                'nama_toko'       => 'Citra Fashion Store',
                'deskripsi_toko'  => 'Pusat fashion wanita terkini dengan harga terjangkau.',
                'alamat_toko'     => 'Bandung',
                'rekening_bank'   => 'Mandiri 9988776655 a/n Citra Lestari',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('toko')->insertBatch($toko);
    }
}
