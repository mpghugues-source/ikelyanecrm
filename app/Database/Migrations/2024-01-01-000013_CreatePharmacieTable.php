<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreatePharmacieTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'       => ['type' => 'INT', 'unsigned' => true],
            'nom'             => ['type' => 'VARCHAR', 'constraint' => 200],
            'categorie'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'forme'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'dosage'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'stock_actuel'    => ['type' => 'INT', 'default' => 0],
            'stock_minimum'   => ['type' => 'INT', 'default' => 10],
            'prix_unitaire'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'fournisseur'     => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'date_expiration' => ['type' => 'DATE', 'null' => true],
            'notes'           => ['type' => 'TEXT', 'null' => true],
            'actif'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('pharmacie');
    }

    public function down(): void
    {
        $this->forge->dropTable('pharmacie', true);
    }
}
