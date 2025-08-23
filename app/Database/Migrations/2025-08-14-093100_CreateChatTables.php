<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatTables extends Migration
{
    public function up()
    {
        // === Tabel Conversations ===
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user1_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user2_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false, // DIPERBAIKI: Sebaiknya tidak null
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false, // DIPERBAIKI: Sebaiknya tidak null
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user1_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user2_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // DITAMBAHKAN: Index untuk pencarian percakapan yang lebih cepat
        $this->forge->addKey(['user1_id', 'user2_id']); 
        $this->forge->createTable('conversations');

        // === Tabel Messages ===
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'conversation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            // DITAMBAHKAN: Kolom untuk menyimpan waktu pesan dibaca
            'read_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false, // DIPERBAIKI: Sebaiknya tidak null
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('conversation_id', 'conversations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('conversation_id');
        $this->forge->createTable('messages');
    }

    public function down()
    {
        $this->forge->dropTable('messages');
        $this->forge->dropTable('conversations');
    }
}