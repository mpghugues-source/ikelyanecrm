<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAmbulancesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'immatriculation'=> ['type' => 'VARCHAR', 'constraint' => 50],
            'modele'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'type_vehicule'  => ['type' => 'ENUM', 'constraint' => ['Ambulance','VSL','SMUR','Autre'], 'default' => 'Ambulance'],
            'statut'         => ['type' => 'ENUM', 'constraint' => ['disponible','en_mission','maintenance','hors_service'], 'default' => 'disponible'],
            'chauffeur_nom'  => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'chauffeur_tel'  => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'date_revision'  => ['type' => 'DATE', 'null' => true],
            'notes'          => ['type' => 'TEXT', 'null' => true],
            'actif'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('ambulances');
    }

    public function down(): void
    {
        $this->forge->dropTable('ambulances', true);
    }
}
