<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMedicationsAndPrescriptionsTable extends Migration
{
    public function up(): void
    {
        // Médicaments
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'dci'         => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true, 'comment' => 'Dénomination Commune Internationale'],
            'forme'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'dosage'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'fabricant'   => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'actif'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('medicaments');

        // Ordonnances
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'    => ['type' => 'INT', 'unsigned' => true],
            'patient_id'   => ['type' => 'INT', 'unsigned' => true],
            'medecin_id'   => ['type' => 'INT', 'unsigned' => true],
            'rdv_id'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'date_ordonnance' => ['type' => 'DATE'],
            'validite_jours'  => ['type' => 'INT', 'default' => 30],
            'instructions' => ['type' => 'TEXT', 'null' => true],
            'statut'       => ['type' => 'ENUM', 'constraint' => ['active','utilisee','expiree'], 'default' => 'active'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['numero', 'tenant_id']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('medecin_id', 'medecins', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ordonnances');

        // Détails ordonnances
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'ordonnance_id'    => ['type' => 'INT', 'unsigned' => true],
            'medicament_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'medicament_libre' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'posologie'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'frequence'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'duree'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'quantite'         => ['type' => 'INT', 'default' => 1],
            'instructions'     => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ordonnance_id', 'ordonnances', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ordonnance_items');
    }

    public function down(): void
    {
        $this->forge->dropTable('ordonnance_items');
        $this->forge->dropTable('ordonnances');
        $this->forge->dropTable('medicaments');
    }
}
