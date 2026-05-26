<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateNaissancesDecesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'         => ['type' => 'INT', 'unsigned' => true],
            'type_evenement'    => ['type' => 'ENUM', 'constraint' => ['naissance','deces'], 'default' => 'naissance'],
            'nom'               => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'date_evenement'    => ['type' => 'DATE'],
            'heure_evenement'   => ['type' => 'TIME', 'null' => true],
            'lieu'              => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'medecin_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero_certificat' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'notes'             => ['type' => 'TEXT', 'null' => true],
            'actif'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('naissances_deces');
    }

    public function down(): void
    {
        $this->forge->dropTable('naissances_deces', true);
    }
}
