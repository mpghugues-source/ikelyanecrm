<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up(): void
    {
        // ── TENANTS ──────────────────────────────────────────────────
        $this->forge->addField([
            'id'          => ['type'=>'INT','auto_increment'=>true],
            'nom'         => ['type'=>'VARCHAR','constraint'=>150],
            'slug'        => ['type'=>'VARCHAR','constraint'=>100],
            'email'       => ['type'=>'VARCHAR','constraint'=>150],
            'telephone'   => ['type'=>'VARCHAR','constraint'=>30,'null'=>true],
            'adresse'     => ['type'=>'TEXT','null'=>true],
            'ville'       => ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'pays'        => ['type'=>'VARCHAR','constraint'=>100,'default'=>'Algérie'],
            'logo'        => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'couleur'     => ['type'=>'VARCHAR','constraint'=>20,'default'=>'#7c3aed'],
            'plan'        => ['type'=>'ENUM','constraint'=>['starter','pro','enterprise'],'default'=>'starter'],
            'actif'       => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'expire_le'   => ['type'=>'DATE','null'=>true],
            'max_users'   => ['type'=>'INT','default'=>5],
            'created_at'  => ['type'=>'DATETIME','null'=>true],
            'updated_at'  => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('tenants');

        // ── USERS ─────────────────────────────────────────────────────
        $this->forge->addField([
            'id'           => ['type'=>'INT','auto_increment'=>true],
            'tenant_id'    => ['type'=>'INT','null'=>true],
            'prenom'       => ['type'=>'VARCHAR','constraint'=>80],
            'nom'          => ['type'=>'VARCHAR','constraint'=>80],
            'email'        => ['type'=>'VARCHAR','constraint'=>150],
            'mot_de_passe' => ['type'=>'VARCHAR','constraint'=>255],
            'role'         => ['type'=>'ENUM','constraint'=>['super_admin','admin','manager','commercial','support'],'default'=>'commercial'],
            'telephone'    => ['type'=>'VARCHAR','constraint'=>30,'null'=>true],
            'avatar'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'locale'       => ['type'=>'VARCHAR','constraint'=>5,'default'=>'fr'],
            'actif'        => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'last_login'   => ['type'=>'DATETIME','null'=>true],
            'reset_token'  => ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'reset_expires'=> ['type'=>'DATETIME','null'=>true],
            'created_at'   => ['type'=>'DATETIME','null'=>true],
            'updated_at'   => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('users');

        // ── CI SESSIONS ───────────────────────────────────────────────
        $this->forge->addField([
            'id'         => ['type'=>'VARCHAR','constraint'=>128,'null'=>false],
            'ip_address' => ['type'=>'VARCHAR','constraint'=>45,'null'=>false],
            'timestamp'  => ['type'=>'TIMESTAMP','null'=>false,'default'=>new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'data'       => ['type'=>'BLOB','null'=>false],
        ]);
        $this->forge->addKey('timestamp');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('ci_sessions');
    }

    public function down(): void
    {
        $this->forge->dropTable('ci_sessions', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('tenants', true);
    }
}
