<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // --- KOLOM BARU UNTUK RELASI TOKO ---
            'toko_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false, // Setiap produk harus punya toko
            ],
            // --- AKHIR KOLOM BARU ---
            'nama_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'gambar_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        // Tambahkan foreign key ke tabel 'toko'
        // Pastikan migrasi untuk tabel 'toko' sudah berjalan sebelumnya
        $this->forge->addForeignKey('toko_id', 'toko', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
