<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenantsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'slug'         => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'adresse'      => ['type' => 'TEXT', 'null' => true],
            'telephone'    => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'logo'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'ville'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pays'         => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Algérie'],
            'couleur'      => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => '#0d6efd'],
            'abonnement'   => ['type' => 'ENUM', 'constraint' => ['gratuit', 'basic', 'premium'], 'default' => 'gratuit'],
            'expire_le'    => ['type' => 'DATE', 'null' => true],
            'actif'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tenants');
    }

    public function down(): void
    {
        $this->forge->dropTable('tenants');
    }
}
