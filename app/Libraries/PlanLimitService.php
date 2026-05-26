<?php

namespace App\Libraries;

class PlanLimitService
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getLimits(int $tenantId): array
    {
        $tenant = $this->db->table('tenants')
                           ->select('plan')
                           ->where('id', $tenantId)
                           ->get()
                           ->getRowArray();

        if (! $tenant) {
            return ['max_medecins' => 0, 'max_patients' => 0, 'plan_nom' => 'Starter'];
        }

        $plan = $this->db->table('subscription_plans')
                         ->select('nom, max_medecins, max_patients')
                         ->where('slug', $tenant['plan'])
                         ->get()
                         ->getRowArray();

        return $plan ?? ['max_medecins' => 0, 'max_patients' => 0, 'plan_nom' => ucfirst($tenant['plan'])];
    }

    public function checkCanAddDoctor(int $tenantId): ?string
    {
        $limits = $this->getLimits($tenantId);
        $max    = (int) $limits['max_medecins'];

        if ($max === 0) return null;

        $current = $this->db->table('users')
                            ->where('tenant_id', $tenantId)
                            ->where('actif', 1)
                            ->whereNotIn('role', ['super_admin'])
                            ->countAllResults();

        if ($current >= $max) {
            return "Votre plan {$limits['plan_nom']} est limité à {$max} utilisateur" . ($max > 1 ? 's' : '') . ". "
                 . "Passez au plan supérieur pour en ajouter davantage.";
        }

        return null;
    }

    public function checkCanAddPatient(int $tenantId): ?string
    {
        $limits = $this->getLimits($tenantId);
        $max    = (int) $limits['max_patients'];

        if ($max === 0) return null;

        $current = $this->db->table('crm_contacts')
                            ->where('tenant_id', $tenantId)
                            ->countAllResults();

        if ($current >= $max) {
            return "Votre plan {$limits['plan_nom']} est limité à {$max} contact" . ($max > 1 ? 's' : '') . ". "
                 . "Passez au plan supérieur pour en ajouter davantage.";
        }

        return null;
    }

    public function getDoctorUsage(int $tenantId): array
    {
        $limits  = $this->getLimits($tenantId);
        $current = $this->db->table('users')
                            ->where('tenant_id', $tenantId)
                            ->where('actif', 1)
                            ->whereNotIn('role', ['super_admin'])
                            ->countAllResults();
        return ['current' => $current, 'max' => (int) $limits['max_medecins'], 'plan_nom' => $limits['plan_nom']];
    }

    public function getPatientUsage(int $tenantId): array
    {
        $limits  = $this->getLimits($tenantId);
        $current = $this->db->table('crm_contacts')
                            ->where('tenant_id', $tenantId)
                            ->countAllResults();
        return ['current' => $current, 'max' => (int) $limits['max_patients'], 'plan_nom' => $limits['plan_nom']];
    }
}
