<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductToMessages extends Migration
{
    public function up()
    {
        // Menambahkan kolom baru untuk menautkan ke produk
        $this->forge->addColumn('messages', [
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Boleh kosong, karena tidak semua pesan tentang produk
                'after'      => 'message',
            ],
        ]);

        // Mengubah kolom 'message' agar boleh kosong (untuk pesan produk)
        $this->forge->modifyColumn('messages', [
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        // Perintah untuk membatalkan migrasi (rollback)
        $this->forge->dropColumn('messages', 'product_id');

        $this->forge->modifyColumn('messages', [
            'message' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ]);
    }
}