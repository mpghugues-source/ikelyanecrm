<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

/**
 * Correction des prix dans subscription_plans.
 * La migration initiale stockait des centimes (4900, 49000) dans une colonne
 * DECIMAL(10,2) prévue pour des euros (49.00, 490.00).
 */
class FixSubscriptionPlanPrices extends Migration
{
    public function up(): void
    {
        $this->db->table('subscription_plans')
            ->where('slug', 'basic')
            ->update(['prix_mensuel' => 49.00, 'prix_annuel' => 490.00]);

        $this->db->table('subscription_plans')
            ->where('slug', 'premium')
            ->update(['prix_mensuel' => 99.00, 'prix_annuel' => 990.00]);
    }

    public function down(): void
    {
        $this->db->table('subscription_plans')
            ->where('slug', 'basic')
            ->update(['prix_mensuel' => 4900, 'prix_annuel' => 49000]);

        $this->db->table('subscription_plans')
            ->where('slug', 'premium')
            ->update(['prix_mensuel' => 9900, 'prix_annuel' => 99000]);
    }
}
