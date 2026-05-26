<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordResetTokens extends Migration
{
    public function up(): void
    {
        // Ajouter colonnes reset password à users
        $this->forge->addColumn('users', [
            'reset_token'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'derniere_connexion'],
            'reset_expires_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'reset_token'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', ['reset_token', 'reset_expires_at']);
    }
}
