<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

/**
 * Table de sessions pour CodeIgniter DatabaseHandler.
 * Remplace le stockage fichier (writable/session/) par la base de données,
 * ce qui permet le multi-serveur et évite les problèmes de permissions.
 *
 * Structure attendue par CodeIgniter\Session\Handlers\DatabaseHandler
 * (source : CI4 user_guide/libraries/sessions.html)
 *
 * La clé primaire dépend de session.matchIP :
 *   - matchIP = false (notre cas) : PRIMARY KEY (id)
 *   - matchIP = true              : PRIMARY KEY (id, ip_address)
 */
class CreateCiSessionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => false,
            ],
            'timestamp' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'data' => [
                'type' => 'BLOB',
                'null' => false,
            ],
        ]);

        // matchIP = false → PRIMARY KEY sur id uniquement
        $this->forge->addKey('id', true);
        // Index sur timestamp pour le garbage collector CI4
        $this->forge->addKey('timestamp');

        $this->forge->createTable('ci_sessions', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('ci_sessions', true);
    }
}
