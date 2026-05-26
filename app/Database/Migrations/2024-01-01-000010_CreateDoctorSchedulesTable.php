<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorSchedulesTable extends Migration
{
    public function up(): void
    {
        // Créneaux bloqués / indisponibilités
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'medecin_id' => ['type' => 'INT', 'unsigned' => true],
            'tenant_id'  => ['type' => 'INT', 'unsigned' => true],
            'date_debut' => ['type' => 'DATETIME'],
            'date_fin'   => ['type' => 'DATETIME'],
            'type'       => ['type' => 'ENUM', 'constraint' => ['indisponible','conge','formation','autre'], 'default' => 'indisponible'],
            'motif'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['medecin_id', 'date_debut']);
        $this->forge->createTable('doctor_schedules');
    }

    public function down(): void
    {
        $this->forge->dropTable('doctor_schedules');
    }
}
