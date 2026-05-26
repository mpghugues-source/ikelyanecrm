<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeleConsultationFields extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('rendez_vous', [
            'lien_tele' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'notes'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('rendez_vous', 'lien_tele');
    }
}
