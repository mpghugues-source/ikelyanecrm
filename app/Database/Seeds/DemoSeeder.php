<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DemoSeeder — Super Admin + tenant démo avec données CRM/ERP
 * Usage : php spark db:seed DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // ── 1. Super Admin (tenant_id = null) ─────────────────────────
        $exists = $this->db->table('users')->where('email', 'superadmin@ikelyanecrm.com')->get()->getRowArray();
        if (!$exists) {
            $this->db->table('users')->insert([
                'tenant_id'    => null,
                'nom'          => 'Admin',
                'prenom'       => 'Super',
                'email'        => 'superadmin@ikelyanecrm.com',
                'mot_de_passe' => password_hash('SuperAdmin@2025', PASSWORD_DEFAULT),
                'role'         => 'super_admin',
                'telephone'    => '+213 21 00 00 00',
                'actif'        => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "  ✓ Super Admin : superadmin@ikelyanecrm.com / SuperAdmin@2025\n";
        }

        // ── 2. Tenant démo — Ikelyane Solutions ───────────────────────
        $tenant = $this->db->table('tenants')->where('slug', 'ikelyane-solutions')->get()->getRowArray();
        if ($tenant) {
            echo "  → Tenant démo déjà existant.\n";
            $tenantId = $tenant['id'];
        } else {
            $this->db->table('tenants')->insert([
                'nom'         => 'Ikelyane Solutions',
                'slug'        => 'ikelyane-solutions',
                'email'       => 'contact@ikelyane.dz',
                'telephone'   => '+213 21 30 40 50',
                'adresse'     => '12 Rue des Technologies, Hydra',
                'ville'       => 'Alger',
                'pays'        => 'Algérie',
                'plan'        => 'pro',
                'max_users'   => 10,
                'expire_le'   => date('Y-m-d', strtotime('+365 days')),
                'actif'       => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
            $tenantId = $this->db->insertID();
            echo "  ✓ Tenant créé : Ikelyane Solutions (ID: $tenantId)\n";
        }

        // ── 3. Utilisateurs du tenant ──────────────────────────────────
        $users = [
            ['prenom'=>'Ahmed',    'nom'=>'Benali',  'email'=>'admin@ikelyane.dz',      'role'=>'admin',      'mdp'=>'Admin@2025'],
            ['prenom'=>'Samira',   'nom'=>'Hadj',    'email'=>'manager@ikelyane.dz',    'role'=>'manager',    'mdp'=>'Manager@2025'],
            ['prenom'=>'Karim',    'nom'=>'Messaoud','email'=>'commercial@ikelyane.dz', 'role'=>'commercial', 'mdp'=>'Commercial@2025'],
            ['prenom'=>'Nadia',    'nom'=>'Zidane',  'email'=>'support@ikelyane.dz',    'role'=>'support',    'mdp'=>'Support@2025'],
        ];
        foreach ($users as $u) {
            if (!$this->db->table('users')->where('email', $u['email'])->get()->getRowArray()) {
                $this->db->table('users')->insert([
                    'tenant_id'=>$tenantId,'prenom'=>$u['prenom'],'nom'=>$u['nom'],
                    'email'=>$u['email'],'mot_de_passe'=>password_hash($u['mdp'],PASSWORD_DEFAULT),
                    'role'=>$u['role'],'actif'=>1,'created_at'=>$now,'updated_at'=>$now,
                ]);
                echo "  ✓ User : {$u['email']} / {$u['mdp']}\n";
            }
        }

        // ── 4. Contacts CRM ───────────────────────────────────────────
        $contacts = [
            ['prenom'=>'Omar',    'nom'=>'Ferhat',    'email'=>'o.ferhat@techco.dz',     'telephone'=>'+213 55 10 20 30','type'=>'lead',     'entreprise'=>'TechCo Algeria',    'poste'=>'DSI',            'secteur'=>'IT','ville'=>'Alger'],
            ['prenom'=>'Leila',   'nom'=>'Boukhari',  'email'=>'l.boukhari@medica.dz',   'telephone'=>'+213 55 20 30 40','type'=>'contact',  'entreprise'=>'Medica Pharma',     'poste'=>'Directrice Achat','secteur'=>'Santé','ville'=>'Oran'],
            ['prenom'=>'Youcef',  'nom'=>'Touati',    'email'=>'y.touati@btp-est.dz',    'telephone'=>'+213 55 30 40 50','type'=>'lead',     'entreprise'=>'BTP Est',           'poste'=>'Gérant',         'secteur'=>'BTP','ville'=>'Constantine'],
            ['prenom'=>'Sara',    'nom'=>'Chérif',    'email'=>'s.cherif@agroalg.dz',    'telephone'=>'+213 55 40 50 60','type'=>'partner',  'entreprise'=>'AgroAlg',           'poste'=>'DG',             'secteur'=>'Agroalimentaire','ville'=>'Sétif'],
            ['prenom'=>'Mohamed', 'nom'=>'Kaci',      'email'=>'m.kaci@financeplus.dz',  'telephone'=>'+213 55 50 60 70','type'=>'contact',  'entreprise'=>'Finance Plus',      'poste'=>'CFO',            'secteur'=>'Finance','ville'=>'Alger'],
            ['prenom'=>'Amira',   'nom'=>'Bensalem',  'email'=>'a.bensalem@logidz.dz',   'telephone'=>'+213 55 60 70 80','type'=>'lead',     'entreprise'=>'LogiDZ',            'poste'=>'Resp. Logistique','secteur'=>'Logistique','ville'=>'Blida'],
        ];
        $contactIds = [];
        foreach ($contacts as $c) {
            if (!$this->db->table('crm_contacts')->where('email', $c['email'])->where('tenant_id', $tenantId)->get()->getRowArray()) {
                $this->db->table('crm_contacts')->insert([
                    'tenant_id'=>$tenantId,'prenom'=>$c['prenom'],'nom'=>$c['nom'],
                    'email'=>$c['email'],'telephone'=>$c['telephone'],'type'=>$c['type'],
                    'entreprise'=>$c['entreprise'],'poste'=>$c['poste'],'secteur'=>$c['secteur'],
                    'ville'=>$c['ville'],'pays'=>'Algérie','actif'=>1,'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
            $contactIds[$c['email']] = $this->db->table('crm_contacts')->where('email', $c['email'])->where('tenant_id', $tenantId)->get()->getRowArray()['id'] ?? null;
        }
        echo "  ✓ " . count($contacts) . " contacts CRM\n";

        // ── 5. Leads ──────────────────────────────────────────────────
        $leads = [
            ['titre'=>'ERP TechCo Algeria',       'contact'=>'o.ferhat@techco.dz',    'valeur'=>850000, 'statut'=>'proposal',    'prob'=>65, 'source'=>'website'],
            ['titre'=>'CRM Medica Pharma',         'contact'=>'l.boukhari@medica.dz',  'valeur'=>480000, 'statut'=>'qualified',   'prob'=>45, 'source'=>'referral'],
            ['titre'=>'Gestion BTP Est',           'contact'=>'y.touati@btp-est.dz',   'valeur'=>320000, 'statut'=>'negotiation', 'prob'=>80, 'source'=>'cold_call'],
            ['titre'=>'Module Finance AgroAlg',    'contact'=>'a.bensalem@logidz.dz',  'valeur'=>190000, 'statut'=>'contacted',   'prob'=>20, 'source'=>'email'],
            ['titre'=>'Tableau de bord Finance+',  'contact'=>'m.kaci@financeplus.dz', 'valeur'=>650000, 'statut'=>'won',         'prob'=>100,'source'=>'event'],
            ['titre'=>'Logistique LogiDZ',         'contact'=>'a.bensalem@logidz.dz',  'valeur'=>270000, 'statut'=>'new',         'prob'=>10, 'source'=>'linkedin'],
        ];
        foreach ($leads as $l) {
            $cid = $contactIds[$l['contact']] ?? null;
            if ($cid && !$this->db->table('crm_leads')->where('titre',$l['titre'])->where('tenant_id',$tenantId)->get()->getRowArray()) {
                $this->db->table('crm_leads')->insert([
                    'tenant_id'=>$tenantId,'contact_id'=>$cid,'titre'=>$l['titre'],
                    'valeur_estimee'=>$l['valeur'],'devise'=>'DZD','source'=>$l['source'],
                    'statut'=>$l['statut'],'probabilite'=>$l['prob'],
                    'date_cloture_prevue'=>date('Y-m-d',strtotime('+'.rand(30,90).' days')),
                    'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
        }
        echo "  ✓ " . count($leads) . " leads CRM\n";

        // ── 6. Activités ─────────────────────────────────────────────
        $activities = [
            ['titre'=>'Appel découverte TechCo',    'type'=>'call',    'contact'=>'o.ferhat@techco.dz',   'statut'=>'done',    'echeance'=>'-5 days'],
            ['titre'=>'Démo ERP TechCo',             'type'=>'demo',    'contact'=>'o.ferhat@techco.dz',   'statut'=>'done',    'echeance'=>'-2 days'],
            ['titre'=>'Envoi proposition Medica',    'type'=>'email',   'contact'=>'l.boukhari@medica.dz', 'statut'=>'done',    'echeance'=>'-3 days'],
            ['titre'=>'Réunion négociation BTP',     'type'=>'meeting', 'contact'=>'y.touati@btp-est.dz',  'statut'=>'pending', 'echeance'=>'+2 days'],
            ['titre'=>'Suivi Finance+ après vente',  'type'=>'call',    'contact'=>'m.kaci@financeplus.dz','statut'=>'pending', 'echeance'=>'+5 days'],
            ['titre'=>'Premier contact LogiDZ',      'type'=>'task',    'contact'=>'a.bensalem@logidz.dz', 'statut'=>'pending', 'echeance'=>'+1 days'],
        ];
        foreach ($activities as $a) {
            $cid = $contactIds[$a['contact']] ?? null;
            if (!$this->db->table('crm_activities')->where('titre',$a['titre'])->where('tenant_id',$tenantId)->get()->getRowArray()) {
                $this->db->table('crm_activities')->insert([
                    'tenant_id'=>$tenantId,'titre'=>$a['titre'],'type'=>$a['type'],
                    'contact_id'=>$cid,'statut'=>$a['statut'],
                    'date_echeance'=>date('Y-m-d H:i:s',strtotime($a['echeance'])),
                    'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
        }
        echo "  ✓ " . count($activities) . " activités CRM\n";

        // ── 7. Fournisseurs ERP ───────────────────────────────────────
        $suppliers = [
            ['nom'=>'Algérie Informatique SARL', 'email'=>'contact@alginf.dz',   'telephone'=>'+213 21 44 55 66','ville'=>'Alger',       'pays'=>'Algérie','ice'=>'099123456789000'],
            ['nom'=>'Maghreb Office',             'email'=>'info@maghreboff.dz',  'telephone'=>'+213 41 22 33 44','ville'=>'Oran',        'pays'=>'Algérie','ice'=>'099234567890000'],
            ['nom'=>'TechMat Fournitures',        'email'=>'tech@techmat.dz',     'telephone'=>'+213 31 55 66 77','ville'=>'Constantine', 'pays'=>'Algérie','ice'=>'099345678901000'],
        ];
        foreach ($suppliers as $s) {
            if (!$this->db->table('erp_suppliers')->where('email',$s['email'])->where('tenant_id',$tenantId)->get()->getRowArray()) {
                $this->db->table('erp_suppliers')->insert(array_merge($s,['tenant_id'=>$tenantId,'actif'=>1,'created_at'=>$now,'updated_at'=>$now]));
            }
        }
        echo "  ✓ " . count($suppliers) . " fournisseurs ERP\n";

        // ── 8. Articles inventaire ────────────────────────────────────
        $items = [
            ['ref'=>'ORD-001','nom'=>'Ordinateurs Portables HP',     'cat'=>'Matériel','unite'=>'unité','qte'=>15,'seuil'=>5,'prix_achat'=>85000,'prix_vente'=>120000],
            ['ref'=>'IMP-001','nom'=>'Imprimante Laser Brother',      'cat'=>'Matériel','unite'=>'unité','qte'=>4,'seuil'=>2,'prix_achat'=>35000,'prix_vente'=>52000],
            ['ref'=>'PAP-001','nom'=>'Ramette Papier A4 80g',         'cat'=>'Fournitures','unite'=>'ramette','qte'=>120,'seuil'=>20,'prix_achat'=>450,'prix_vente'=>650],
            ['ref'=>'LOG-001','nom'=>'Licence Microsoft Office 365',  'cat'=>'Logiciel','unite'=>'licence','qte'=>8,'seuil'=>2,'prix_achat'=>18000,'prix_vente'=>25000],
        ];
        foreach ($items as $i) {
            if (!$this->db->table('erp_inventory_items')->where('reference',$i['ref'])->where('tenant_id',$tenantId)->get()->getRowArray()) {
                $this->db->table('erp_inventory_items')->insert([
                    'tenant_id'=>$tenantId,'reference'=>$i['ref'],'nom'=>$i['nom'],
                    'categorie'=>$i['cat'],'unite'=>$i['unite'],'quantite_stock'=>$i['qte'],
                    'seuil_alerte'=>$i['seuil'],'prix_achat'=>$i['prix_achat'],'prix_vente'=>$i['prix_vente'],
                    'actif'=>1,'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
        }
        echo "  ✓ " . count($items) . " articles inventaire ERP\n";

        // ── 9. Comptes & Transactions ──────────────────────────────────
        $accountId = null;
        if (!$this->db->table('erp_accounts')->where('nom','Compte Principal')->where('tenant_id',$tenantId)->get()->getRowArray()) {
            $this->db->table('erp_accounts')->insert([
                'tenant_id'=>$tenantId,'nom'=>'Compte Principal','type'=>'checking',
                'banque'=>'BNA','solde'=>5000000,'actif'=>1,'created_at'=>$now,'updated_at'=>$now,
            ]);
            $accountId = $this->db->insertID();
        } else {
            $accountId = $this->db->table('erp_accounts')->where('nom','Compte Principal')->where('tenant_id',$tenantId)->get()->getRowArray()['id'];
        }

        $txns = [
            ['desc'=>'Vente ERP Finance+',    'type'=>'income', 'montant'=>650000, 'cat'=>'Ventes',    'date'=>'-20 days'],
            ['desc'=>'Abonnement Cloud',       'type'=>'expense','montant'=>45000,  'cat'=>'Charges IT','date'=>'-15 days'],
            ['desc'=>'Vente CRM TechCo (acompte)','type'=>'income','montant'=>300000,'cat'=>'Ventes',  'date'=>'-10 days'],
            ['desc'=>'Matériel Bureautique',   'type'=>'expense','montant'=>95000,  'cat'=>'Achats',    'date'=>'-8 days'],
            ['desc'=>'Salaires Avril',         'type'=>'expense','montant'=>480000, 'cat'=>'Charges RH','date'=>'-5 days'],
            ['desc'=>'Prestation Conseil',     'type'=>'income', 'montant'=>120000, 'cat'=>'Services',  'date'=>'-3 days'],
        ];
        foreach ($txns as $t) {
            if (!$this->db->table('erp_transactions')->where('description',$t['desc'])->where('tenant_id',$tenantId)->get()->getRowArray()) {
                $this->db->table('erp_transactions')->insert([
                    'tenant_id'=>$tenantId,'account_id'=>$accountId,'type'=>$t['type'],
                    'montant'=>$t['montant'],'description'=>$t['desc'],'categorie'=>$t['cat'],
                    'date_transaction'=>date('Y-m-d',strtotime($t['date'])),'statut'=>'posted',
                    'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
        }
        echo "  ✓ " . count($txns) . " transactions ERP\n";

        echo "\n  ✅ Seeder terminé avec succès!\n";
        echo "  ─────────────────────────────────────────────────────\n";
        echo "  Super Admin : superadmin@ikelyanecrm.com / SuperAdmin@2025\n";
        echo "  Admin demo  : admin@ikelyane.dz / Admin@2025\n";
        echo "  Manager     : manager@ikelyane.dz / Manager@2025\n";
        echo "  Commercial  : commercial@ikelyane.dz / Commercial@2025\n";
        echo "  Support     : support@ikelyane.dz / Support@2025\n";
        echo "  ─────────────────────────────────────────────────────\n";
    }
}
