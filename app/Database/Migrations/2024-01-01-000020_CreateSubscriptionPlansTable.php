<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateSubscriptionPlansTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'prix_mensuel'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'prix_annuel'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'max_medecins'  => ['type' => 'INT', 'default' => 0, 'comment' => '0 = illimité'],
            'max_patients'  => ['type' => 'INT', 'default' => 0, 'comment' => '0 = illimité'],
            'features'      => ['type' => 'JSON', 'null' => true],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'ordre'         => ['type' => 'INT', 'default' => 0],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('subscription_plans');

        // Données de départ
        $this->db->table('subscription_plans')->insertBatch([
            [
                'nom'          => 'Gratuit',
                'slug'         => 'gratuit',
                'prix_mensuel' => 0,
                'prix_annuel'  => 0,
                'max_medecins' => 2,
                'max_patients' => 100,
                'features'     => json_encode(['Gestion patients', 'Rendez-vous', 'Ordonnances']),
                'is_active'    => 1,
                'ordre'        => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nom'          => 'Basic',
                'slug'         => 'basic',
                'prix_mensuel' => 4900,
                'prix_annuel'  => 49000,
                'max_medecins' => 10,
                'max_patients' => 1000,
                'features'     => json_encode(['Tout Gratuit', 'Pharmacie', 'Laboratoire', 'Export PDF/CSV']),
                'is_active'    => 1,
                'ordre'        => 2,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nom'          => 'Premium',
                'slug'         => 'premium',
                'prix_mensuel' => 9900,
                'prix_annuel'  => 99000,
                'max_medecins' => 0,
                'max_patients' => 0,
                'features'     => json_encode(['Tout Basic', 'Radiologie', 'Ambulances', 'RH', 'Rapports avancés', 'Support prioritaire']),
                'is_active'    => 1,
                'ordre'        => 3,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('subscription_plans', true);
    }
}
