<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSellerNotesToOrders extends Migration
{
    /**
     * Menambahkan kolom seller_notes ke tabel 'orders'
     */
    public function up()
    {
        $this->forge->addColumn('orders', [
            'seller_notes' => [
                'type'  => 'TEXT', // Tipe data TEXT untuk pesan yang bisa panjang
                'null'  => true,   // Boleh kosong (opsional)
                'after' => 'payment_method', // Diletakkan setelah kolom payment_method
            ],
        ]);
    }

    /**
     * Menghapus kolom seller_notes dari tabel 'orders' jika migrasi di-rollback
     */
    public function down()
    {
        $this->forge->dropColumn('orders', 'seller_notes');
    }
}