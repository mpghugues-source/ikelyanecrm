<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'    => ['type' => 'INT', 'unsigned' => true],
            'nom'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'mot_de_passe' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'         => ['type' => 'ENUM', 'constraint' => ['super_admin','admin','medecin','secretaire','patient'], 'default' => 'patient'],
            'telephone'    => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'avatar'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'actif'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'derniere_connexion' => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['email', 'tenant_id']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down(): void
    {
        $this->forge->dropTable('users');
    }
}
