<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateRadiologieTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'INT', 'unsigned' => true],
            'patient_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'medecin_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'type_examen'   => ['type' => 'ENUM', 'constraint' => ['Radiographie','Échographie','Scanner','IRM','Mammographie','Autre'], 'default' => 'Radiographie'],
            'region'        => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'date_demande'  => ['type' => 'DATE'],
            'date_examen'   => ['type' => 'DATE', 'null' => true],
            'statut'        => ['type' => 'ENUM', 'constraint' => ['en_attente','programme','realise','annule'], 'default' => 'en_attente'],
            'urgence'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'compte_rendu'  => ['type' => 'TEXT', 'null' => true],
            'actif'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('radiologie');
    }

    public function down(): void
    {
        $this->forge->dropTable('radiologie', true);
    }
}
