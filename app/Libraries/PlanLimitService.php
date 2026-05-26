<?php

namespace App\Libraries;

/**
 * Vérifie les limites de plan (max_medecins, max_patients) pour un tenant.
 * 0 = illimité dans subscription_plans.
 */
class PlanLimitService
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Retourne les limites du plan actif d'un tenant.
     * ['max_medecins' => int, 'max_patients' => int, 'plan_nom' => string]
     */
    public function getLimits(int $tenantId): array
    {
        $tenant = $this->db->table('tenants')
                           ->select('abonnement')
                           ->where('id', $tenantId)
                           ->get()
                           ->getRowArray();

        if (! $tenant) {
            return ['max_medecins' => 0, 'max_patients' => 0, 'plan_nom' => 'Gratuit'];
        }

        $plan = $this->db->table('subscription_plans')
                         ->select('nom, max_medecins, max_patients')
                         ->where('slug', $tenant['abonnement'])
                         ->get()
                         ->getRowArray();

        return $plan ?? ['max_medecins' => 0, 'max_patients' => 0, 'plan_nom' => ucfirst($tenant['abonnement'])];
    }

    /**
     * Vérifie si le tenant peut ajouter un médecin.
     * Retourne null si autorisé, ou un message d'erreur si bloqué.
     */
    public function checkCanAddDoctor(int $tenantId): ?string
    {
        $limits = $this->getLimits($tenantId);
        $max    = (int) $limits['max_medecins'];

        if ($max === 0) {
            return null; // illimité
        }

        $current = $this->db->table('medecins')
                            ->where('tenant_id', $tenantId)
                            ->where('actif', 1)
                            ->countAllResults();

        if ($current >= $max) {
            return "Votre plan {$limits['plan_nom']} est limité à {$max} médecin" . ($max > 1 ? 's' : '') . ". "
                 . "Vous en avez actuellement {$current}. "
                 . "Passez au plan supérieur pour en ajouter davantage.";
        }

        return null;
    }

    /**
     * Vérifie si le tenant peut ajouter un patient.
     * Retourne null si autorisé, ou un message d'erreur si bloqué.
     */
    public function checkCanAddPatient(int $tenantId): ?string
    {
        $limits = $this->getLimits($tenantId);
        $max    = (int) $limits['max_patients'];

        if ($max === 0) {
            return null; // illimité
        }

        $current = $this->db->table('patients')
                            ->where('tenant_id', $tenantId)
                            ->where('actif', 1)
                            ->countAllResults();

        if ($current >= $max) {
            return "Votre plan {$limits['plan_nom']} est limité à {$max} patient" . ($max > 1 ? 's' : '') . ". "
                 . "Vous en avez actuellement {$current}. "
                 . "Passez au plan supérieur pour en ajouter davantage.";
        }

        return null;
    }

    /**
     * Retourne un tableau ['current' => int, 'max' => int] pour affichage dans les vues.
     */
    public function getDoctorUsage(int $tenantId): array
    {
        $limits  = $this->getLimits($tenantId);
        $current = $this->db->table('medecins')
                            ->where('tenant_id', $tenantId)
                            ->where('actif', 1)
                            ->countAllResults();
        return ['current' => $current, 'max' => (int) $limits['max_medecins'], 'plan_nom' => $limits['plan_nom']];
    }

    /**
     * Retourne un tableau ['current' => int, 'max' => int] pour affichage dans les vues.
     */
    public function getPatientUsage(int $tenantId): array
    {
        $limits  = $this->getLimits($tenantId);
        $current = $this->db->table('patients')
                            ->where('tenant_id', $tenantId)
                            ->where('actif', 1)
                            ->countAllResults();
        return ['current' => $current, 'max' => (int) $limits['max_patients'], 'plan_nom' => $limits['plan_nom']];
    }
}
