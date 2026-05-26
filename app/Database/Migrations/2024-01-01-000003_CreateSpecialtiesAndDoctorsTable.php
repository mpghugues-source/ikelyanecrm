<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpecialtiesAndDoctorsTable extends Migration
{
    public function up(): void
    {
        // Spécialités médicales
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom'   => ['type' => 'VARCHAR', 'constraint' => 150],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('specialites');

        // Profils médecins
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'tenant_id'     => ['type' => 'INT', 'unsigned' => true],
            'specialite_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero_ordre'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'biographie'    => ['type' => 'TEXT', 'null' => true],
            'tarif_consultation' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'duree_consultation' => ['type' => 'INT', 'default' => 30, 'comment' => 'minutes'],
            'jours_travail' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => '1,2,3,4,5', 'comment' => 'jours: 1=Lun'],
            'heure_debut'   => ['type' => 'TIME', 'default' => '08:00:00'],
            'heure_fin'     => ['type' => 'TIME', 'default' => '17:00:00'],
            'actif'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('medecins');
    }

    public function down(): void
    {
        $this->forge->dropTable('medecins');
        $this->forge->dropTable('specialites');
    }
}
