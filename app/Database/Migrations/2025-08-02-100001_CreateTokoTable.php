<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTokoTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_toko' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'deskripsi_toko' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'alamat_toko' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'rekening_bank' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Contoh: BCA 123456789 a/n John Doe',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('toko');
    }

    public function down()
    {
        $this->forge->dropTable('toko');
    }
}
