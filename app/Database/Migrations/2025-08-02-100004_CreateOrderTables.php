<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderTables extends Migration
{
    public function up()
    {
        // 1. Buat Tabel `pesanan`
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'ID Pembeli',
            ],
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'status_pembayaran' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pesanan');

        // 2. Buat Tabel `detail_pesanan`
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pesanan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'produk_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'toko_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'ID Toko Penjual',
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'harga_saat_beli' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status_pesanan_penjual' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'],
                'default'    => 'pending',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pesanan_id', 'pesanan', 'id', 'CASCADE', 'CASCADE');
        // --- PERBAIKAN DI SINI ---
        $this->forge->addForeignKey('produk_id', 'products', 'id', 'CASCADE', 'CASCADE'); // Mengubah 'produk' menjadi 'products'
        // --- AKHIR PERBAIKAN ---
        $this->forge->addForeignKey('toko_id', 'toko', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_pesanan');
    }

    public function down()
    {
        // Hapus tabel dengan urutan terbalik karena ada foreign key
        $this->forge->dropTable('detail_pesanan');
        $this->forge->dropTable('pesanan');
    }
}
