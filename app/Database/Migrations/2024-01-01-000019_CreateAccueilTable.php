<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAccueilTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'patient_id'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nom_visiteur'   => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'type_operation' => ['type' => 'ENUM', 'constraint' => ['admission','sortie','consultation','visite','urgence'], 'default' => 'consultation'],
            'date_heure'     => ['type' => 'DATETIME'],
            'motif'          => ['type' => 'VARCHAR', 'constraint' => 300, 'null' => true],
            'chambre'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'statut'         => ['type' => 'ENUM', 'constraint' => ['en_attente','en_cours','termine'], 'default' => 'en_attente'],
            'notes'          => ['type' => 'TEXT', 'null' => true],
            'actif'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('accueil');
    }

    public function down(): void
    {
        $this->forge->dropTable('accueil', true);
    }
}
