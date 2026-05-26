<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'           => ['type' => 'INT', 'unsigned' => true],
            'plan_slug'           => ['type' => 'VARCHAR', 'constraint' => 50],
            'billing_cycle'       => ['type' => 'ENUM', 'constraint' => ['monthly', 'annual'], 'default' => 'monthly'],
            'amount'              => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'currency'            => ['type' => 'VARCHAR', 'constraint' => 3, 'default' => 'EUR'],
            'status'              => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'failed', 'cancelled'], 'default' => 'pending'],
            'tx_ref'              => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'flw_transaction_id'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'flw_payment_type'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'customer_email'      => ['type' => 'VARCHAR', 'constraint' => 191],
            'customer_name'       => ['type' => 'VARCHAR', 'constraint' => 191],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addUniqueKey('tx_ref');
        $this->forge->createTable('payments');
    }

    public function down(): void
    {
        $this->forge->dropTable('payments', true);
    }
}
