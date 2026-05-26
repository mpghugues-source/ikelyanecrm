<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now   = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        /*
         * ══════════════════════════════════════════════════════════════
         * IDENTIFIANTS DE DÉMONSTRATION
         * ══════════════════════════════════════════════════════════════
         * Tenant 1 – Clinique IkelyaneMed (Kinshasa, RD Congo) — Premium
         *   SuperAdmin : superadmin@ikelyanemed.com  / SuperAdmin@2024
         *   Admin      : admin@ikelyanemed.com        / Admin@IkelyaneMed24
         *   Médecin 1  : dr.mulamba@ikelyanemed.com  / Medecin@IkelyaneMed24
         *   Médecin 2  : dr.kabila@ikelyanemed.com   / Medecin@IkelyaneMed24
         *   Patient    : grace.mutombo@email.com      / Patient@IkelyaneMed24
         *
         * Tenant 2 – Centre Médical Bondeko (Kinshasa, RD Congo) — Premium
         *   Admin      : admin@bondeko-medical.cd      / Admin@Bondeko24
         *   Médecin 1  : dr.mbeki@bondeko-medical.cd   / Medecin@Bondeko24
         *   Médecin 2  : dr.ngalula@bondeko-medical.cd / Medecin@Bondeko24
         *
         * Tenant 3 – Clinique Saint-Luc (Lyon, France) — Basic
         *   Admin      : admin@clinique-saintluc.fr    / Admin@SaintLuc24
         *   Médecin 1  : dr.martin@clinique-saintluc.fr / Medecin@SaintLuc24
         *   Médecin 2  : dr.bernard@clinique-saintluc.fr / Medecin@SaintLuc24
         *
         * Tenant 4 – Clinique Al Farabi (Casablanca, Maroc) — Premium
         *   Admin      : admin@clinique-alfarabi.ma    / Admin@AlFarabi24
         *   Médecin 1  : dr.benali@clinique-alfarabi.ma  / Medecin@AlFarabi24
         *   Médecin 2  : dr.tahiri@clinique-alfarabi.ma  / Medecin@AlFarabi24
         *   Médecin 3  : dr.alaoui@clinique-alfarabi.ma  / Medecin@AlFarabi24
         * ══════════════════════════════════════════════════════════════
         */

        // ── Tenant ────────────────────────────────────────────────────
        $this->db->table('tenants')->insert([
            'nom'        => 'Clinique IkelyaneMed',
            'slug'       => 'ikelyanemed',
            'adresse'    => '15 Avenue du Flambeau, Gombe',
            'telephone'  => '+243 81 000 0000',
            'email'      => 'contact@ikelyanemed.com',
            'ville'      => 'Kinshasa',
            'pays'       => 'RD Congo',
            'couleur'    => '#0d6efd',
            'abonnement' => 'premium',
            'actif'      => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $tenantId = $this->db->insertID();

        // ── Spécialités ───────────────────────────────────────────────
        $specialites = [
            ['nom' => 'General Medicine'],
            ['nom' => 'Cardiology'],
            ['nom' => 'Pediatrics'],
            ['nom' => 'Gynecology'],
            ['nom' => 'Dermatology'],
            ['nom' => 'Ophthalmology'],
            ['nom' => 'Neurology'],
            ['nom' => 'Orthopedics'],
        ];
        $this->db->table('specialites')->insertBatch($specialites);

        // ── Utilisateurs ──────────────────────────────────────────────

        // Admin
        $this->db->table('users')->insert([
            'tenant_id'    => $tenantId,
            'nom'          => 'Administrateur',
            'prenom'       => 'Super',
            'email'        => 'admin@ikelyanemed.com',
            'mot_de_passe' => password_hash('Admin@IkelyaneMed24', PASSWORD_DEFAULT),
            'role'         => 'admin',
            'telephone'    => '+243 81 000 0001',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        // Médecin 1 — Généraliste
        $this->db->table('users')->insert([
            'tenant_id'    => $tenantId,
            'nom'          => 'Mulamba',
            'prenom'       => 'Jean-Pierre',
            'email'        => 'dr.mulamba@ikelyanemed.com',
            'mot_de_passe' => password_hash('Medecin@IkelyaneMed24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+243 81 000 0002',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $medecinUserId1 = $this->db->insertID();

        $this->db->table('medecins')->insert([
            'user_id'            => $medecinUserId1,
            'tenant_id'          => $tenantId,
            'specialite_id'      => 1,
            'numero_ordre'       => 'RDC-001234',
            'tarif_consultation' => 2000,
            'duree_consultation' => 30,
            'heure_debut'        => '08:00:00',
            'heure_fin'          => '17:00:00',
            'actif'              => 1,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);
        $medecinId1 = $this->db->insertID();

        // Médecin 2 — Cardiologue
        $this->db->table('users')->insert([
            'tenant_id'    => $tenantId,
            'nom'          => 'Kabila',
            'prenom'       => 'Céline',
            'email'        => 'dr.kabila@ikelyanemed.com',
            'mot_de_passe' => password_hash('Medecin@IkelyaneMed24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+243 81 000 0003',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $medecinUserId2 = $this->db->insertID();

        $this->db->table('medecins')->insert([
            'user_id'            => $medecinUserId2,
            'tenant_id'          => $tenantId,
            'specialite_id'      => 2,
            'numero_ordre'       => 'RDC-005678',
            'tarif_consultation' => 3500,
            'duree_consultation' => 45,
            'heure_debut'        => '09:00:00',
            'heure_fin'          => '16:00:00',
            'actif'              => 1,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);
        $medecinId2 = $this->db->insertID();

        // Patient 1 (avec compte utilisateur)
        $this->db->table('users')->insert([
            'tenant_id'    => $tenantId,
            'nom'          => 'Mutombo',
            'prenom'       => 'Grace',
            'email'        => 'grace.mutombo@email.com',
            'mot_de_passe' => password_hash('Patient@IkelyaneMed24', PASSWORD_DEFAULT),
            'role'         => 'patient',
            'telephone'    => '+243 81 11 22 33',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $patientUserId = $this->db->insertID();

        $this->db->table('patients')->insert([
            'tenant_id'      => $tenantId,
            'user_id'        => $patientUserId,
            'numero_dossier' => 'PAT-2024-001',
            'nom'            => 'Mutombo',
            'prenom'         => 'Grace',
            'date_naissance' => '1985-03-15',
            'sexe'           => 'F',
            'telephone'      => '+243 81 11 22 33',
            'email'          => 'grace.mutombo@email.com',
            'ville'          => 'Kinshasa',
            'groupe_sanguin' => 'A+',
            'allergies'      => 'Pénicilline',
            'antecedents'    => 'Hypertension artérielle (depuis 2018)',
            'actif'          => 1,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
        $patientId1 = $this->db->insertID();

        // Patient 2 (sans compte)
        $this->db->table('patients')->insert([
            'tenant_id'      => $tenantId,
            'numero_dossier' => 'PAT-2024-002',
            'nom'            => 'Kasongo',
            'prenom'         => 'Patrick',
            'date_naissance' => '1972-07-20',
            'sexe'           => 'M',
            'telephone'      => '+243 81 44 55 66',
            'email'          => 'patrick.kasongo@email.com',
            'ville'          => 'Lubumbashi',
            'groupe_sanguin' => 'O+',
            'actif'          => 1,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
        $patientId2 = $this->db->insertID();

        // Patient 3
        $this->db->table('patients')->insert([
            'tenant_id'      => $tenantId,
            'numero_dossier' => 'PAT-2024-003',
            'nom'            => 'Tshisekedi',
            'prenom'         => 'Marie',
            'date_naissance' => '1990-11-05',
            'sexe'           => 'F',
            'telephone'      => '+243 81 77 88 99',
            'ville'          => 'Goma',
            'groupe_sanguin' => 'B+',
            'actif'          => 1,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
        $patientId3 = $this->db->insertID();

        // ── Services ──────────────────────────────────────────────────
        $services = [
            ['tenant_id' => $tenantId, 'nom' => 'Consultation Générale',    'code' => 'CONS-GEN',  'prix' => 2000, 'actif' => 1],
            ['tenant_id' => $tenantId, 'nom' => 'Consultation Spécialisée', 'code' => 'CONS-SPEC', 'prix' => 3500, 'actif' => 1],
            ['tenant_id' => $tenantId, 'nom' => 'Électrocardiogramme (ECG)','code' => 'ECG',        'prix' => 1500, 'actif' => 1],
            ['tenant_id' => $tenantId, 'nom' => 'Prise de sang / Bilan',    'code' => 'BILAN',     'prix' => 800,  'actif' => 1],
            ['tenant_id' => $tenantId, 'nom' => 'Radiographie',             'code' => 'RADIO',     'prix' => 2500, 'actif' => 1],
        ];
        $this->db->table('services')->insertBatch($services);

        // ── Médicaments ───────────────────────────────────────────────
        $medicaments = [
            ['nom' => 'Paracétamol 500mg',   'dci' => 'Paracétamol',              'forme' => 'Comprimé', 'dosage' => '500mg',  'actif' => 1],
            ['nom' => 'Amoxicilline 1g',      'dci' => 'Amoxicilline',             'forme' => 'Comprimé', 'dosage' => '1g',     'actif' => 1],
            ['nom' => 'Ibuprofène 400mg',     'dci' => 'Ibuprofène',               'forme' => 'Comprimé', 'dosage' => '400mg',  'actif' => 1],
            ['nom' => 'Doliprane 1000mg',     'dci' => 'Paracétamol',              'forme' => 'Comprimé', 'dosage' => '1000mg', 'actif' => 1],
            ['nom' => 'Aspirine 500mg',       'dci' => 'Acide acétylsalicylique',  'forme' => 'Comprimé', 'dosage' => '500mg',  'actif' => 1],
            ['nom' => 'Métronidazole 500mg',  'dci' => 'Métronidazole',            'forme' => 'Comprimé', 'dosage' => '500mg',  'actif' => 1],
            ['nom' => 'Oméprazole 20mg',      'dci' => 'Oméprazole',               'forme' => 'Gélule',   'dosage' => '20mg',   'actif' => 1],
            ['nom' => 'Atorvastatine 20mg',   'dci' => 'Atorvastatine',            'forme' => 'Comprimé', 'dosage' => '20mg',   'actif' => 1],
            ['nom' => 'Amlodipine 5mg',       'dci' => 'Amlodipine',               'forme' => 'Comprimé', 'dosage' => '5mg',    'actif' => 1],
            ['nom' => 'Metformine 850mg',     'dci' => 'Metformine',               'forme' => 'Comprimé', 'dosage' => '850mg',  'actif' => 1],
        ];
        $this->db->table('medicaments')->insertBatch($medicaments);
        $med1Id = $this->db->insertID() - 9; // first medication id

        // ── Rendez-vous ───────────────────────────────────────────────
        $rdvs = [
            // Aujourd'hui
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId1, 'medecin_id' => $medecinId1,
                'date_rdv'   => $today, 'heure_rdv' => '09:00:00', 'duree' => 30,
                'motif'      => 'Contrôle tension artérielle', 'statut' => 'confirme',
                'type_rdv'   => 'suivi', 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId2, 'medecin_id' => $medecinId1,
                'date_rdv'   => $today, 'heure_rdv' => '10:00:00', 'duree' => 30,
                'motif'      => 'Consultation générale', 'statut' => 'planifie',
                'type_rdv'   => 'consultation', 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId3, 'medecin_id' => $medecinId2,
                'date_rdv'   => $today, 'heure_rdv' => '11:00:00', 'duree' => 45,
                'motif'      => 'Bilan cardiaque', 'statut' => 'confirme',
                'type_rdv'   => 'consultation', 'created_at' => $now, 'updated_at' => $now,
            ],
            // Passés
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId1, 'medecin_id' => $medecinId1,
                'date_rdv'   => date('Y-m-d', strtotime('-7 days')), 'heure_rdv' => '09:30:00', 'duree' => 30,
                'motif'      => 'Céphalées persistantes', 'statut' => 'termine',
                'type_rdv'   => 'consultation', 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId2, 'medecin_id' => $medecinId2,
                'date_rdv'   => date('Y-m-d', strtotime('-14 days')), 'heure_rdv' => '14:00:00', 'duree' => 45,
                'motif'      => 'Douleurs thoraciques', 'statut' => 'termine',
                'type_rdv'   => 'urgence', 'created_at' => $now, 'updated_at' => $now,
            ],
            // À venir
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId1, 'medecin_id' => $medecinId2,
                'date_rdv'   => date('Y-m-d', strtotime('+3 days')), 'heure_rdv' => '10:30:00', 'duree' => 45,
                'motif'      => 'Bilan cardiologique', 'statut' => 'planifie',
                'type_rdv'   => 'consultation', 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'tenant_id'  => $tenantId, 'patient_id' => $patientId3, 'medecin_id' => $medecinId1,
                'date_rdv'   => date('Y-m-d', strtotime('+5 days')), 'heure_rdv' => '15:00:00', 'duree' => 30,
                'motif'      => 'Résultats analyses', 'statut' => 'planifie',
                'type_rdv'   => 'suivi', 'created_at' => $now, 'updated_at' => $now,
            ],
        ];
        $this->db->table('rendez_vous')->insertBatch($rdvs);
        $rdvId1 = $this->db->insertID() - 6; // first rdv id (the completed one)

        // ── Dossiers médicaux ─────────────────────────────────────────
        $this->db->table('dossiers_medicaux')->insert([
            'tenant_id'         => $tenantId,
            'patient_id'        => $patientId1,
            'medecin_id'        => $medecinId1,
            'date_consultation' => date('Y-m-d', strtotime('-7 days')),
            'motif'             => 'Céphalées persistantes',
            'symptomes'         => 'Maux de tête intenses depuis 3 jours, aggravés le matin.',
            'examen_clinique'   => 'TA: 145/90 mmHg. Pas de déficit neurologique. Fond d\'œil normal.',
            'diagnostic'        => 'Hypertension artérielle mal contrôlée',
            'traitement'        => 'Adaptation du traitement antihypertenseur. Repos. Hydratation.',
            'observations'      => 'Revoir dans 7 jours avec bilan biologique.',
            'tension_arterielle'=> '145/90',
            'poids'             => 68.5,
            'taille'            => 162.0,
            'temperature'       => 37.0,
            'pouls'             => 82,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        // ── Ordonnances ───────────────────────────────────────────────
        $this->db->table('ordonnances')->insert([
            'tenant_id'       => $tenantId,
            'patient_id'      => $patientId1,
            'medecin_id'      => $medecinId1,
            'numero'          => 'ORD-2024-0001',
            'date_ordonnance' => date('Y-m-d', strtotime('-7 days')),
            'validite_jours'  => 30,
            'instructions'    => 'Prendre les médicaments selon la prescription. Éviter l\'alcool.',
            'statut'          => 'active',
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);
        $ordonnanceId = $this->db->insertID();

        $this->db->table('ordonnance_items')->insertBatch([
            [
                'ordonnance_id'    => $ordonnanceId,
                'medicament_id'    => null,
                'medicament_libre' => 'Amlodipine 5mg',
                'posologie'        => '1 comprimé le matin',
                'frequence'        => '1 fois/jour',
                'duree'            => '1 mois',
                'quantite'         => 30,
            ],
            [
                'ordonnance_id'    => $ordonnanceId,
                'medicament_id'    => null,
                'medicament_libre' => 'Captopril 25mg',
                'posologie'        => '1 comprimé matin et soir',
                'frequence'        => '2 fois/jour',
                'duree'            => '1 mois',
                'quantite'         => 60,
            ],
        ]);

        // ── Super Admin (plateforme) ──────────────────────────────────
        $this->db->table('users')->insert([
            'tenant_id'    => $tenantId,
            'nom'          => 'Admin',
            'prenom'       => 'Super',
            'email'        => 'superadmin@ikelyanemed.com',
            'mot_de_passe' => password_hash('SuperAdmin@2024', PASSWORD_DEFAULT),
            'role'         => 'super_admin',
            'telephone'    => '+243 81 000 0000',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        // ══════════════════════════════════════════════════════════════
        // CLINIQUE 2 — Centre Médical Bondeko · Kinshasa · RD Congo · Premium
        // ══════════════════════════════════════════════════════════════
        $this->db->table('tenants')->insert([
            'nom'        => 'Centre Médical Bondeko',
            'slug'       => 'centre-medical-bondeko',
            'adresse'    => 'Boulevard du 30 Juin, Gombe',
            'telephone'  => '+243 81 234 5678',
            'email'      => 'contact@bondeko-medical.cd',
            'ville'      => 'Kinshasa',
            'pays'       => 'RD Congo',
            'couleur'    => '#0e7490',
            'abonnement' => 'premium',
            'expire_le'  => '2026-12-31',
            'actif'      => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $t2 = $this->db->insertID();

        $this->db->table('users')->insert([
            'tenant_id'    => $t2,
            'nom'          => 'Lumumba',
            'prenom'       => 'Hervé',
            'email'        => 'admin@bondeko-medical.cd',
            'mot_de_passe' => password_hash('Admin@Bondeko24', PASSWORD_DEFAULT),
            'role'         => 'admin',
            'telephone'    => '+243 81 234 5679',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        // Médecin cardiologue
        $this->db->table('users')->insert([
            'tenant_id'    => $t2,
            'nom'          => 'Mbeki',
            'prenom'       => 'Thierry',
            'email'        => 'dr.mbeki@bondeko-medical.cd',
            'mot_de_passe' => password_hash('Medecin@Bondeko24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+243 81 234 5680',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t2_m1 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t2_m1, 'tenant_id' => $t2, 'specialite_id' => 2,
            'numero_ordre' => 'BDK-010001', 'tarif_consultation' => 4000,
            'duree_consultation' => 45, 'heure_debut' => '08:00:00', 'heure_fin' => '16:00:00',
            'biographie' => 'Cardiologue interventionnel, 20 ans d\'expérience à Kinshasa.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Médecin neurologue
        $this->db->table('users')->insert([
            'tenant_id'    => $t2,
            'nom'          => 'Ngalula',
            'prenom'       => 'Clarisse',
            'email'        => 'dr.ngalula@bondeko-medical.cd',
            'mot_de_passe' => password_hash('Medecin@Bondeko24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+243 81 234 5681',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t2_m2 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t2_m2, 'tenant_id' => $t2, 'specialite_id' => 7,
            'numero_ordre' => 'BDK-010002', 'tarif_consultation' => 3500,
            'duree_consultation' => 30, 'heure_debut' => '09:00:00', 'heure_fin' => '17:00:00',
            'biographie' => 'Spécialiste en neurologie clinique, Université de Kinshasa.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Patients Bondeko
        $this->db->table('patients')->insertBatch([
            ['tenant_id' => $t2, 'numero_dossier' => 'BDK-2024-001', 'nom' => 'Ilunga', 'prenom' => 'Cédric',
             'date_naissance' => '1965-08-12', 'sexe' => 'M', 'telephone' => '+243 82 50 11 22',
             'ville' => 'Kinshasa', 'groupe_sanguin' => 'AB+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t2, 'numero_dossier' => 'BDK-2024-002', 'nom' => 'Nsimba', 'prenom' => 'Brigitte',
             'date_naissance' => '1978-03-22', 'sexe' => 'F', 'telephone' => '+243 82 50 33 44',
             'ville' => 'Kinshasa', 'groupe_sanguin' => 'A-', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t2, 'numero_dossier' => 'BDK-2024-003', 'nom' => 'Mbuyi', 'prenom' => 'Christian',
             'date_naissance' => '1990-06-30', 'sexe' => 'M', 'telephone' => '+243 82 50 55 66',
             'ville' => 'Brazzaville', 'groupe_sanguin' => 'O-', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t2, 'numero_dossier' => 'BDK-2024-004', 'nom' => 'Kalonda', 'prenom' => 'Espérance',
             'date_naissance' => '1955-11-08', 'sexe' => 'F', 'telephone' => '+243 82 50 77 88',
             'ville' => 'Lubumbashi', 'groupe_sanguin' => 'B+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t2, 'numero_dossier' => 'BDK-2024-005', 'nom' => 'Diallo', 'prenom' => 'Pascal',
             'date_naissance' => '1982-09-15', 'sexe' => 'M', 'telephone' => '+243 82 50 99 00',
             'ville' => 'Kinshasa', 'groupe_sanguin' => 'A+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ══════════════════════════════════════════════════════════════
        // CLINIQUE 3 — Clinique Saint-Luc · Lyon · France · Basic
        // ══════════════════════════════════════════════════════════════
        $this->db->table('tenants')->insert([
            'nom'        => 'Clinique Saint-Luc',
            'slug'       => 'clinique-saint-luc',
            'adresse'    => '18 Rue de la République, Part-Dieu',
            'telephone'  => '+33 4 72 10 20 30',
            'email'      => 'contact@clinique-saintluc.fr',
            'ville'      => 'Lyon',
            'pays'       => 'France',
            'couleur'    => '#059669',
            'abonnement' => 'basic',
            'expire_le'  => '2026-06-30',
            'actif'      => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $t3 = $this->db->insertID();

        $this->db->table('users')->insert([
            'tenant_id'    => $t3,
            'nom'          => 'Dupont',
            'prenom'       => 'Laurent',
            'email'        => 'admin@clinique-saintluc.fr',
            'mot_de_passe' => password_hash('Admin@SaintLuc24', PASSWORD_DEFAULT),
            'role'         => 'admin',
            'telephone'    => '+33 4 72 10 20 31',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        // Médecin généraliste
        $this->db->table('users')->insert([
            'tenant_id'    => $t3,
            'nom'          => 'Martin',
            'prenom'       => 'Claire',
            'email'        => 'dr.martin@clinique-saintluc.fr',
            'mot_de_passe' => password_hash('Medecin@SaintLuc24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+33 6 10 20 30 40',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t3_m1 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t3_m1, 'tenant_id' => $t3, 'specialite_id' => 1,
            'numero_ordre' => 'FR-LYN-020001', 'tarif_consultation' => 25,
            'duree_consultation' => 20, 'heure_debut' => '08:30:00', 'heure_fin' => '17:30:00',
            'biographie' => 'Médecin généraliste, prise en charge globale des patients à Lyon.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Médecin dermatologue
        $this->db->table('users')->insert([
            'tenant_id'    => $t3,
            'nom'          => 'Bernard',
            'prenom'       => 'Sophie',
            'email'        => 'dr.bernard@clinique-saintluc.fr',
            'mot_de_passe' => password_hash('Medecin@SaintLuc24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+33 6 10 20 30 41',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t3_m2 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t3_m2, 'tenant_id' => $t3, 'specialite_id' => 5,
            'numero_ordre' => 'FR-LYN-020002', 'tarif_consultation' => 55,
            'duree_consultation' => 25, 'heure_debut' => '09:00:00', 'heure_fin' => '16:00:00',
            'biographie' => 'Dermatologue spécialisée en dermatologie esthétique et médicale.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Patients Saint-Luc
        $this->db->table('patients')->insertBatch([
            ['tenant_id' => $t3, 'numero_dossier' => 'SLC-2024-001', 'nom' => 'Leroy', 'prenom' => 'Isabelle',
             'date_naissance' => '1988-04-17', 'sexe' => 'F', 'telephone' => '+33 6 61 11 22 33',
             'ville' => 'Lyon', 'groupe_sanguin' => 'B-', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t3, 'numero_dossier' => 'SLC-2024-002', 'nom' => 'Moreau', 'prenom' => 'Pierre',
             'date_naissance' => '1975-12-01', 'sexe' => 'M', 'telephone' => '+33 6 61 33 44 55',
             'ville' => 'Villeurbanne', 'groupe_sanguin' => 'O+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t3, 'numero_dossier' => 'SLC-2024-003', 'nom' => 'Petit', 'prenom' => 'Nathalie',
             'date_naissance' => '1993-07-25', 'sexe' => 'F', 'telephone' => '+33 6 61 55 66 77',
             'ville' => 'Grenoble', 'groupe_sanguin' => 'A+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ══════════════════════════════════════════════════════════════
        // CLINIQUE 4 — Clinique Al Farabi · Casablanca · Maroc · Premium
        // ══════════════════════════════════════════════════════════════
        $this->db->table('tenants')->insert([
            'nom'        => 'Clinique Al Farabi',
            'slug'       => 'clinique-al-farabi',
            'adresse'    => '27 Boulevard Zerktouni, Quartier des Hôpitaux',
            'telephone'  => '+212 5 22 48 00 00',
            'email'      => 'contact@clinique-alfarabi.ma',
            'ville'      => 'Casablanca',
            'pays'       => 'Maroc',
            'couleur'    => '#7c3aed',
            'abonnement' => 'premium',
            'expire_le'  => '2026-12-31',
            'actif'      => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $t4 = $this->db->insertID();

        $this->db->table('users')->insert([
            'tenant_id'    => $t4,
            'nom'          => 'El Mansouri',
            'prenom'       => 'Karim',
            'email'        => 'admin@clinique-alfarabi.ma',
            'mot_de_passe' => password_hash('Admin@AlFarabi24', PASSWORD_DEFAULT),
            'role'         => 'admin',
            'telephone'    => '+212 5 22 48 00 01',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        // Médecin pédiatre
        $this->db->table('users')->insert([
            'tenant_id'    => $t4,
            'nom'          => 'Benali',
            'prenom'       => 'Nadia',
            'email'        => 'dr.benali@clinique-alfarabi.ma',
            'mot_de_passe' => password_hash('Medecin@AlFarabi24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+212 6 61 10 20 30',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t4_m1 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t4_m1, 'tenant_id' => $t4, 'specialite_id' => 3,
            'numero_ordre' => 'MA-CSB-030001', 'tarif_consultation' => 300,
            'duree_consultation' => 30, 'heure_debut' => '08:00:00', 'heure_fin' => '15:00:00',
            'biographie' => 'Pédiatre avec expertise en néonatologie, CHU Ibn Rochd de Casablanca.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Médecin gynécologue
        $this->db->table('users')->insert([
            'tenant_id'    => $t4,
            'nom'          => 'Tahiri',
            'prenom'       => 'Zineb',
            'email'        => 'dr.tahiri@clinique-alfarabi.ma',
            'mot_de_passe' => password_hash('Medecin@AlFarabi24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+212 6 61 10 20 31',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t4_m2 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t4_m2, 'tenant_id' => $t4, 'specialite_id' => 4,
            'numero_ordre' => 'MA-CSB-030002', 'tarif_consultation' => 400,
            'duree_consultation' => 40, 'heure_debut' => '09:00:00', 'heure_fin' => '17:00:00',
            'biographie' => 'Gynécologue-obstétricienne, suivi grossesse et chirurgie laparoscopique.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Médecin orthopédiste
        $this->db->table('users')->insert([
            'tenant_id'    => $t4,
            'nom'          => 'Alaoui',
            'prenom'       => 'Youssef',
            'email'        => 'dr.alaoui@clinique-alfarabi.ma',
            'mot_de_passe' => password_hash('Medecin@AlFarabi24', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => '+212 6 61 10 20 32',
            'actif'        => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $u_t4_m3 = $this->db->insertID();
        $this->db->table('medecins')->insert([
            'user_id' => $u_t4_m3, 'tenant_id' => $t4, 'specialite_id' => 8,
            'numero_ordre' => 'MA-CSB-030003', 'tarif_consultation' => 500,
            'duree_consultation' => 35, 'heure_debut' => '08:30:00', 'heure_fin' => '16:30:00',
            'biographie' => 'Chirurgien orthopédiste spécialisé en traumatologie du sport.',
            'actif' => 1, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Patients Al Farabi
        $this->db->table('patients')->insertBatch([
            ['tenant_id' => $t4, 'numero_dossier' => 'AFB-2024-001', 'nom' => 'Chraibi', 'prenom' => 'Fatima',
             'date_naissance' => '2015-03-10', 'sexe' => 'F', 'telephone' => '+212 6 61 11 22 33',
             'ville' => 'Casablanca', 'groupe_sanguin' => 'A+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t4, 'numero_dossier' => 'AFB-2024-002', 'nom' => 'Belkadi', 'prenom' => 'Sara',
             'date_naissance' => '1996-08-20', 'sexe' => 'F', 'telephone' => '+212 6 61 33 44 55',
             'ville' => 'Casablanca', 'groupe_sanguin' => 'O+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t4, 'numero_dossier' => 'AFB-2024-003', 'nom' => 'Ouazzani', 'prenom' => 'Hassan',
             'date_naissance' => '1970-01-15', 'sexe' => 'M', 'telephone' => '+212 6 61 55 66 77',
             'ville' => 'Marrakech', 'groupe_sanguin' => 'B+', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $t4, 'numero_dossier' => 'AFB-2024-004', 'nom' => 'Bennani', 'prenom' => 'Leila',
             'date_naissance' => '2000-05-05', 'sexe' => 'F', 'telephone' => '+212 6 61 77 88 99',
             'ville' => 'Rabat', 'groupe_sanguin' => 'AB-', 'actif' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ── Factures ──────────────────────────────────────────────────
        $this->db->table('factures')->insert([
            'tenant_id'    => $tenantId,
            'patient_id'   => $patientId1,
            'medecin_id'   => $medecinId1,
            'numero'       => 'FAC-2024-0001',
            'date_facture' => date('Y-m-d', strtotime('-7 days')),
            'sous_total'   => 2000,
            'remise'       => 0,
            'total'        => 2000,
            'montant_paye' => 2000,
            'statut'       => 'paye',
            'mode_paiement'=> 'especes',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $factureId = $this->db->insertID();

        $this->db->table('facture_items')->insert([
            'facture_id'    => $factureId,
            'service_id'    => 1,
            'description'   => 'Consultation Générale',
            'quantite'      => 1,
            'prix_unitaire' => 2000,
            'total'         => 2000,
        ]);

        // Deuxième facture (en attente)
        $this->db->table('factures')->insert([
            'tenant_id'    => $tenantId,
            'patient_id'   => $patientId2,
            'medecin_id'   => $medecinId2,
            'numero'       => 'FAC-2024-0002',
            'date_facture' => date('Y-m-d', strtotime('-14 days')),
            'sous_total'   => 5000,
            'remise'       => 500,
            'total'        => 4500,
            'montant_paye' => 0,
            'statut'       => 'en_attente',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $factureId2 = $this->db->insertID();

        $this->db->table('facture_items')->insertBatch([
            [
                'facture_id'    => $factureId2,
                'service_id'    => 2,
                'description'   => 'Consultation Spécialisée (Cardiologie)',
                'quantite'      => 1,
                'prix_unitaire' => 3500,
                'total'         => 3500,
            ],
            [
                'facture_id'    => $factureId2,
                'service_id'    => 3,
                'description'   => 'Électrocardiogramme (ECG)',
                'quantite'      => 1,
                'prix_unitaire' => 1500,
                'total'         => 1500,
            ],
        ]);

        // ── Paiements d'abonnement (cohérence DB) ─────────────────────
        // Tenant 1 — IkelyaneMed Premium annuel (renouvelé Jan 2025 et Jan 2026)
        $this->db->table('payments')->insertBatch([
            [
                'tenant_id'          => $tenantId,
                'plan_slug'          => 'premium',
                'billing_cycle'      => 'annual',
                'amount'             => 990.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-1-ANNUAL-2025',
                'flw_transaction_id' => 'FLW-MOCK-10001',
                'flw_payment_type'   => 'card',
                'customer_email'     => 'admin@ikelyanemed.com',
                'customer_name'      => 'Clinique IkelyaneMed',
                'created_at'         => '2025-01-15 10:00:00',
                'updated_at'         => '2025-01-15 10:02:00',
            ],
            [
                'tenant_id'          => $tenantId,
                'plan_slug'          => 'premium',
                'billing_cycle'      => 'annual',
                'amount'             => 990.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-1-ANNUAL-2026',
                'flw_transaction_id' => 'FLW-MOCK-10002',
                'flw_payment_type'   => 'card',
                'customer_email'     => 'admin@ikelyanemed.com',
                'customer_name'      => 'Clinique IkelyaneMed',
                'created_at'         => '2026-01-10 09:30:00',
                'updated_at'         => '2026-01-10 09:31:00',
            ],

            // Tenant 2 — Bondeko Premium annuel (Jan 2026)
            [
                'tenant_id'          => $t2,
                'plan_slug'          => 'premium',
                'billing_cycle'      => 'annual',
                'amount'             => 990.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-2-ANNUAL-2026',
                'flw_transaction_id' => 'FLW-MOCK-20001',
                'flw_payment_type'   => 'mobilemoney',
                'customer_email'     => 'admin@bondeko-medical.cd',
                'customer_name'      => 'Centre Médical Bondeko',
                'created_at'         => '2026-01-05 11:15:00',
                'updated_at'         => '2026-01-05 11:16:00',
            ],

            // Tenant 3 — Saint-Luc Basic annuel (Jul 2025)
            [
                'tenant_id'          => $t3,
                'plan_slug'          => 'basic',
                'billing_cycle'      => 'annual',
                'amount'             => 490.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-3-ANNUAL-2025',
                'flw_transaction_id' => 'FLW-MOCK-30001',
                'flw_payment_type'   => 'card',
                'customer_email'     => 'admin@clinique-saintluc.fr',
                'customer_name'      => 'Clinique Saint-Luc',
                'created_at'         => '2025-07-01 14:00:00',
                'updated_at'         => '2025-07-01 14:01:00',
            ],

            // Tenant 3 — Saint-Luc Basic mensuel x2 (historique avant passage annuel)
            [
                'tenant_id'          => $t3,
                'plan_slug'          => 'basic',
                'billing_cycle'      => 'monthly',
                'amount'             => 49.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-3-MONTHLY-2025-05',
                'flw_transaction_id' => 'FLW-MOCK-30002',
                'flw_payment_type'   => 'card',
                'customer_email'     => 'admin@clinique-saintluc.fr',
                'customer_name'      => 'Clinique Saint-Luc',
                'created_at'         => '2025-05-01 09:00:00',
                'updated_at'         => '2025-05-01 09:01:00',
            ],
            [
                'tenant_id'          => $t3,
                'plan_slug'          => 'basic',
                'billing_cycle'      => 'monthly',
                'amount'             => 49.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-3-MONTHLY-2025-06',
                'flw_transaction_id' => 'FLW-MOCK-30003',
                'flw_payment_type'   => 'card',
                'customer_email'     => 'admin@clinique-saintluc.fr',
                'customer_name'      => 'Clinique Saint-Luc',
                'created_at'         => '2025-06-01 09:05:00',
                'updated_at'         => '2025-06-01 09:06:00',
            ],

            // Tenant 4 — Al Farabi Premium annuel (Jan 2026)
            [
                'tenant_id'          => $t4,
                'plan_slug'          => 'premium',
                'billing_cycle'      => 'annual',
                'amount'             => 990.00,
                'currency'           => 'EUR',
                'status'             => 'paid',
                'tx_ref'             => 'IMED-4-ANNUAL-2026',
                'flw_transaction_id' => 'FLW-MOCK-40001',
                'flw_payment_type'   => 'card',
                'customer_email'     => 'admin@clinique-alfarabi.ma',
                'customer_name'      => 'Clinique Al Farabi',
                'created_at'         => '2026-01-03 08:45:00',
                'updated_at'         => '2026-01-03 08:46:00',
            ],
        ]);
    }
}
