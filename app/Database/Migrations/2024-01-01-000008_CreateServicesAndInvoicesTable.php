<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesAndInvoicesTable extends Migration
{
    public function up(): void
    {
        // Services / Actes médicaux
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'INT', 'unsigned' => true],
            'nom'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'code'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'prix'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => true],
            'actif'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('services');

        // Factures
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'INT', 'unsigned' => true],
            'patient_id'    => ['type' => 'INT', 'unsigned' => true],
            'medecin_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'rdv_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'date_facture'  => ['type' => 'DATE'],
            'sous_total'    => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'remise'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'total'         => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'montant_paye'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'statut'        => ['type' => 'ENUM', 'constraint' => ['en_attente','partiel','paye','annule'], 'default' => 'en_attente'],
            'mode_paiement' => ['type' => 'ENUM', 'constraint' => ['especes','cheque','carte','virement','autre'], 'null' => true],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['numero', 'tenant_id']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('factures');

        // Lignes de facture
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'facture_id'   => ['type' => 'INT', 'unsigned' => true],
            'service_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'description'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'quantite'     => ['type' => 'INT', 'default' => 1],
            'prix_unitaire'=> ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'total'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('facture_id', 'factures', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('facture_items');
    }

    public function down(): void
    {
        $this->forge->dropTable('facture_items');
        $this->forge->dropTable('factures');
        $this->forge->dropTable('services');
    }
}
