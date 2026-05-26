<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatientsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'           => ['type' => 'INT', 'unsigned' => true],
            'user_id'             => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero_dossier'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'nom'                 => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom'              => ['type' => 'VARCHAR', 'constraint' => 100],
            'date_naissance'      => ['type' => 'DATE', 'null' => true],
            'sexe'                => ['type' => 'ENUM', 'constraint' => ['M','F',''], 'default' => ''],
            'telephone'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email'               => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'adresse'             => ['type' => 'TEXT', 'null' => true],
            'ville'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'groupe_sanguin'      => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'allergies'           => ['type' => 'TEXT', 'null' => true],
            'antecedents'         => ['type' => 'TEXT', 'null' => true],
            'traitement_en_cours' => ['type' => 'TEXT', 'null' => true],
            'contact_urgence_nom' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'contact_urgence_tel' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'assurance'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'numero_secu'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'notes'               => ['type' => 'TEXT', 'null' => true],
            'actif'               => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['numero_dossier', 'tenant_id']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('patients');
    }

    public function down(): void
    {
        $this->forge->dropTable('patients');
    }
}
