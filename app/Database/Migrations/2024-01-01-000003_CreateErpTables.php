<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateErpTables extends Migration
{
    public function up(): void
    {
        // ERP SUPPLIERS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'nom'=>['type'=>'VARCHAR','constraint'=>150],'email'=>['type'=>'VARCHAR','constraint'=>150,'null'=>true],
            'telephone'=>['type'=>'VARCHAR','constraint'=>30,'null'=>true],'adresse'=>['type'=>'TEXT','null'=>true],
            'ville'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],'pays'=>['type'=>'VARCHAR','constraint'=>100,'default'=>'Algérie'],
            'ice'=>['type'=>'VARCHAR','constraint'=>50,'null'=>true],'nif'=>['type'=>'VARCHAR','constraint'=>50,'null'=>true],
            'conditions_paiement'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'contact_principal'=>['type'=>'VARCHAR','constraint'=>150,'null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['active','inactive'],'default'=>'active'],
            'notes'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_suppliers');

        // ERP INVENTORY
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'code'=>['type'=>'VARCHAR','constraint'=>50],'nom'=>['type'=>'VARCHAR','constraint'=>200],
            'description'=>['type'=>'TEXT','null'=>true],'categorie'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'unite'=>['type'=>'VARCHAR','constraint'=>30,'default'=>'pièce'],
            'quantite_stock'=>['type'=>'DECIMAL','constraint'=>'15,3','default'=>0],
            'quantite_min'=>['type'=>'DECIMAL','constraint'=>'15,3','default'=>0],
            'prix_achat'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'prix_vente'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'supplier_id'=>['type'=>'INT','null'=>true],'emplacement'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'date_expiration'=>['type'=>'DATE','null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['active','inactive'],'default'=>'active'],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_inventory_items');

        // ERP INVENTORY MOVEMENTS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'item_id'=>['type'=>'INT'],'type'=>['type'=>'ENUM','constraint'=>['in','out','adjustment','return'],'default'=>'in'],
            'quantite'=>['type'=>'DECIMAL','constraint'=>'15,3'],'prix_unit'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'reference'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],'motif'=>['type'=>'VARCHAR','constraint'=>200,'null'=>true],
            'notes'=>['type'=>'TEXT','null'=>true],'created_by'=>['type'=>'INT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey(['tenant_id','item_id']);
        $this->forge->createTable('erp_inventory_movements');

        // ERP PURCHASE ORDERS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'numero'=>['type'=>'VARCHAR','constraint'=>50],'supplier_id'=>['type'=>'INT','null'=>true],
            'date_commande'=>['type'=>'DATE'],'date_livraison_prevue'=>['type'=>'DATE','null'=>true],
            'date_livraison_reelle'=>['type'=>'DATE','null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['draft','sent','partial','received','cancelled'],'default'=>'draft'],
            'montant_ht'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'tva'=>['type'=>'DECIMAL','constraint'=>'5,2','default'=>0],
            'montant_ttc'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'devise'=>['type'=>'VARCHAR','constraint'=>10,'default'=>'DZD'],
            'notes'=>['type'=>'TEXT','null'=>true],'created_by'=>['type'=>'INT','null'=>true],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_purchase_orders');

        // ERP PO ITEMS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'po_id'=>['type'=>'INT'],
            'item_id'=>['type'=>'INT','null'=>true],'description'=>['type'=>'VARCHAR','constraint'=>255],
            'quantite'=>['type'=>'DECIMAL','constraint'=>'15,3'],'unite'=>['type'=>'VARCHAR','constraint'=>30,'default'=>'pièce'],
            'prix_unitaire'=>['type'=>'DECIMAL','constraint'=>'15,2'],'tva'=>['type'=>'DECIMAL','constraint'=>'5,2','default'=>0],
            'montant_total'=>['type'=>'DECIMAL','constraint'=>'15,2'],'quantite_recue'=>['type'=>'DECIMAL','constraint'=>'15,3','default'=>0],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('po_id');
        $this->forge->createTable('erp_purchase_order_items');

        // ERP ACCOUNTS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'code'=>['type'=>'VARCHAR','constraint'=>20],'nom'=>['type'=>'VARCHAR','constraint'=>200],
            'type'=>['type'=>'ENUM','constraint'=>['asset','liability','equity','revenue','expense'],'default'=>'expense'],
            'solde'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'description'=>['type'=>'TEXT','null'=>true],'parent_id'=>['type'=>'INT','null'=>true],
            'actif'=>['type'=>'TINYINT','constraint'=>1,'default'=>1],'created_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_accounts');

        // ERP TRANSACTIONS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'reference'=>['type'=>'VARCHAR','constraint'=>50],'date_transaction'=>['type'=>'DATE'],
            'description'=>['type'=>'VARCHAR','constraint'=>255],
            'type'=>['type'=>'ENUM','constraint'=>['income','expense','transfer'],'default'=>'expense'],
            'montant'=>['type'=>'DECIMAL','constraint'=>'15,2'],'account_id'=>['type'=>'INT','null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['draft','validated','cancelled'],'default'=>'validated'],
            'notes'=>['type'=>'TEXT','null'=>true],'created_by'=>['type'=>'INT','null'=>true],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_transactions');

        // ERP BUDGETS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'nom'=>['type'=>'VARCHAR','constraint'=>200],'exercice'=>['type'=>'YEAR'],
            'periode'=>['type'=>'ENUM','constraint'=>['annual','q1','q2','q3','q4','monthly'],'default'=>'annual'],
            'account_id'=>['type'=>'INT','null'=>true],
            'montant_prevu'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'montant_realise'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'notes'=>['type'=>'TEXT','null'=>true],'created_by'=>['type'=>'INT','null'=>true],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_budgets');

        // ERP PROJECTS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'tenant_id'=>['type'=>'INT'],
            'nom'=>['type'=>'VARCHAR','constraint'=>200],'description'=>['type'=>'TEXT','null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['planning','active','on_hold','completed','cancelled'],'default'=>'planning'],
            'priorite'=>['type'=>'ENUM','constraint'=>['low','medium','high','critical'],'default'=>'medium'],
            'date_debut'=>['type'=>'DATE','null'=>true],'date_fin'=>['type'=>'DATE','null'=>true],
            'budget'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'cout_reel'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],
            'avancement'=>['type'=>'TINYINT','default'=>0],'chef_projet'=>['type'=>'INT','null'=>true],
            'created_by'=>['type'=>'INT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('tenant_id');
        $this->forge->createTable('erp_projects');

        // ERP PROJECT TASKS
        $this->forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'project_id'=>['type'=>'INT'],
            'titre'=>['type'=>'VARCHAR','constraint'=>200],'description'=>['type'=>'TEXT','null'=>true],
            'statut'=>['type'=>'ENUM','constraint'=>['todo','in_progress','done','cancelled'],'default'=>'todo'],
            'priorite'=>['type'=>'ENUM','constraint'=>['low','medium','high'],'default'=>'medium'],
            'assigned_to'=>['type'=>'INT','null'=>true],'date_echeance'=>['type'=>'DATE','null'=>true],
            'heures_estimees'=>['type'=>'DECIMAL','constraint'=>'8,2','default'=>0],
            'heures_reelles'=>['type'=>'DECIMAL','constraint'=>'8,2','default'=>0],
            'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addPrimaryKey('id'); $this->forge->addKey('project_id');
        $this->forge->createTable('erp_project_tasks');
    }

    public function down(): void
    {
        foreach (['erp_project_tasks','erp_projects','erp_budgets','erp_transactions','erp_accounts',
                  'erp_purchase_order_items','erp_purchase_orders','erp_inventory_movements',
                  'erp_inventory_items','erp_suppliers'] as $t)
            $this->forge->dropTable($t, true);
    }
}
