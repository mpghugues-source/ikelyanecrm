<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMedicalRecordsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'INT', 'unsigned' => true],
            'patient_id'    => ['type' => 'INT', 'unsigned' => true],
            'medecin_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'rdv_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'date_consultation' => ['type' => 'DATE'],
            'motif'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'symptomes'     => ['type' => 'TEXT', 'null' => true],
            'examen_clinique'   => ['type' => 'TEXT', 'null' => true],
            'diagnostic'    => ['type' => 'TEXT', 'null' => true],
            'traitement'    => ['type' => 'TEXT', 'null' => true],
            'observations'  => ['type' => 'TEXT', 'null' => true],
            'tension_arterielle' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'poids'         => ['type' => 'DECIMAL', 'constraint' => '5,1', 'null' => true],
            'taille'        => ['type' => 'DECIMAL', 'constraint' => '5,1', 'null' => true],
            'temperature'   => ['type' => 'DECIMAL', 'constraint' => '4,1', 'null' => true],
            'pouls'         => ['type' => 'INT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('medecin_id', 'medecins', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('dossiers_medicaux');
    }

    public function down(): void
    {
        $this->forge->dropTable('dossiers_medicaux');
    }
}
