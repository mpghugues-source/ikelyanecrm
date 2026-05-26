<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppointmentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'    => ['type' => 'INT', 'unsigned' => true],
            'patient_id'   => ['type' => 'INT', 'unsigned' => true],
            'medecin_id'   => ['type' => 'INT', 'unsigned' => true],
            'date_rdv'     => ['type' => 'DATE'],
            'heure_rdv'    => ['type' => 'TIME'],
            'duree'        => ['type' => 'INT', 'default' => 30, 'comment' => 'minutes'],
            'motif'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'statut'       => ['type' => 'ENUM', 'constraint' => ['planifie','confirme','en_cours','termine','annule','absent'], 'default' => 'planifie'],
            'type_rdv'          => ['type' => 'ENUM', 'constraint' => ['consultation','suivi','urgence','teleconsultation'], 'default' => 'consultation'],
            'type_consultation' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'notes'        => ['type' => 'TEXT', 'null' => true],
            'rappel_envoye'=> ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['date_rdv', 'medecin_id']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('medecin_id', 'medecins', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rendez_vous');
    }

    public function down(): void
    {
        $this->forge->dropTable('rendez_vous');
    }
}
