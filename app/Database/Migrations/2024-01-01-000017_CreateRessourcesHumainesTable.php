<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateRessourcesHumainesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'INT', 'unsigned' => true],
            'nom'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'poste'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'departement'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'date_embauche' => ['type' => 'DATE', 'null' => true],
            'salaire'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'telephone'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'contrat'       => ['type' => 'ENUM', 'constraint' => ['CDI','CDD','Vacation','Stage','Autre'], 'default' => 'CDI'],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'actif'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('ressources_humaines');
    }

    public function down(): void
    {
        $this->forge->dropTable('ressources_humaines', true);
    }
}
