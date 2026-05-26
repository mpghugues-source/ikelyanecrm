<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateCrmTables extends Migration
{
    public function up(): void
    {
        // CRM CONTACTS
        $this->forge->addField([
            'id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'type'=>['type'=>'ENUM','constraint'=>['lead','contact','client','supplier','partner'],'default'=>'contact'],
            'prenom'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],'nom'=>['type'=>'VARCHAR','constraint'=>100],
            'email'=>['type'=>'VARCHAR','constraint'=>150,'null'=>true],'telephone'=>['type'=>'VARCHAR','constraint'=>30,'null'=>true],
            'mobile'=>['type'=>'VARCHAR','constraint'=>30,'null'=>true],'entreprise'=>['type'=>'VARCHAR','constraint'=>150,'null'=>true],
            'poste'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],'secteur'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'adresse'=>['type'=>'TEXT','null'=>true],'ville'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'pays'=>['type'=>'VARCHAR','constraint'=>100,'default'=>'Algérie'],'site_web'=>['type'=>'VARCHAR','constraint'=>200,'null'=>true],
            'linkedin'=>['type'=>'VARCHAR','constraint'=>200,'null'=>true],'source'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['active','inactive'],'default'=>'active'],
            'notes'=>['type'=>'TEXT','null'=>true],'tags'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_by'=>['type'=>'INT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('crm_contacts');

        // CRM LEADS
        $this->forge->addField([
            'id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'contact_id'=>['type'=>'INT','null'=>true],'titre'=>['type'=>'VARCHAR','constraint'=>200],
            'valeur_estimee'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],'devise'=>['type'=>'VARCHAR','constraint'=>10,'default'=>'DZD'],
            'source'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['new','qualified','proposition','negotiation','won','lost'],'default'=>'new'],
            'probabilite'=>['type'=>'TINYINT','default'=>20],'date_cloture_prevue'=>['type'=>'DATE','null'=>true],
            'date_cloture_reelle'=>['type'=>'DATE','null'=>true],'raison_perte'=>['type'=>'TEXT','null'=>true],
            'notes'=>['type'=>'TEXT','null'=>true],'assigned_to'=>['type'=>'INT','null'=>true],
            'created_by'=>['type'=>'INT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('crm_leads');

        // CRM ACTIVITIES
        $this->forge->addField([
            'id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'type'=>['type'=>'ENUM','constraint'=>['call','email','meeting','task','note','demo'],'default'=>'task'],
            'sujet'=>['type'=>'VARCHAR','constraint'=>200],'description'=>['type'=>'TEXT','null'=>true],
            'date_debut'=>['type'=>'DATETIME','null'=>true],'date_fin'=>['type'=>'DATETIME','null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['planned','completed','cancelled'],'default'=>'planned'],
            'priorite'=>['type'=>'ENUM','constraint'=>['low','medium','high'],'default'=>'medium'],
            'contact_id'=>['type'=>'INT','null'=>true],'lead_id'=>['type'=>'INT','null'=>true],
            'assigned_to'=>['type'=>'INT','null'=>true],'created_by'=>['type'=>'INT','null'=>true],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('crm_activities');

        // CRM CASES
        $this->forge->addField([
            'id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'numero'=>['type'=>'VARCHAR','constraint'=>50],'sujet'=>['type'=>'VARCHAR','constraint'=>200],
            'description'=>['type'=>'TEXT','null'=>true],'type'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'priorite'=>['type'=>'ENUM','constraint'=>['low','medium','high','urgent'],'default'=>'medium'],
            'statut'=>['type'=>'ENUM','constraint'=>['open','in_progress','pending','resolved','closed'],'default'=>'open'],
            'contact_id'=>['type'=>'INT','null'=>true],'assigned_to'=>['type'=>'INT','null'=>true],
            'date_resolution'=>['type'=>'DATETIME','null'=>true],'resolution'=>['type'=>'TEXT','null'=>true],
            'created_by'=>['type'=>'INT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('crm_cases');

        // CRM CAMPAIGNS
        $this->forge->addField([
            'id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'nom'=>['type'=>'VARCHAR','constraint'=>200],'description'=>['type'=>'TEXT','null'=>true],
            'type'=>['type'=>'ENUM','constraint'=>['email','sms','event','social','other'],'default'=>'email'],
            'statut'=>['type'=>'ENUM','constraint'=>['draft','active','completed','cancelled'],'default'=>'draft'],
            'budget'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],'cout_reel'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'nb_cibles'=>['type'=>'INT','default'=>0],'nb_reponses'=>['type'=>'INT','default'=>0],
            'date_debut'=>['type'=>'DATE','null'=>true],'date_fin'=>['type'=>'DATE','null'=>true],
            'objectif'=>['type'=>'TEXT','null'=>true],'created_by'=>['type'=>'INT','null'=>true],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('crm_campaigns');
    }

    public function down(): void
    {
        foreach (['crm_campaigns','crm_cases','crm_activities','crm_leads','crm_contacts'] as $t)
            $this->forge->dropTable($t, true);
    }
}
