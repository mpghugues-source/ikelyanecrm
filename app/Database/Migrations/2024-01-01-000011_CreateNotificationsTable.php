<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'  => ['type' => 'INT', 'unsigned' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'type'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'titre'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'message'    => ['type' => 'TEXT'],
            'lien'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'icone'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'bi-bell'],
            'couleur'    => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'primary'],
            'lu'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'user_id', 'lu']);
        $this->forge->createTable('notifications');
    }

    public function down(): void
    {
        $this->forge->dropTable('notifications');
    }
}
