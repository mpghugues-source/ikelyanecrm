<?php
namespace App\Models;
use CodeIgniter\Model;

class SubscriptionPlanModel extends Model
{
    protected $table         = 'subscription_plans';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['nom','slug','prix_mensuel','prix_annuel','max_medecins','max_patients','features','is_active','ordre'];

    public function getActive(): array
    {
        return $this->where('is_active', 1)->orderBy('ordre')->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Retourne les plans payants (basic + premium) dans le format attendu
     * par PaymentController et SubscriptionController.
     * Les couleurs sont UI-only et restent hardcodées ici.
     *
     * @return array<string, array{nom:string, mensuel:float, annuel:float, features:array, color:string}>
     */
    public function getPlansForCheckout(): array
    {
        $colors = ['basic' => '#1a56db', 'premium' => '#7c3aed'];
        $rows   = $this->where('is_active', 1)
                       ->whereIn('slug', ['basic', 'premium'])
                       ->orderBy('ordre')
                       ->findAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['slug']] = [
                'nom'      => $row['nom'],
                'mensuel'  => (float) $row['prix_mensuel'],
                'annuel'   => (float) $row['prix_annuel'],
                'features' => is_string($row['features'])
                    ? json_decode($row['features'], true)
                    : ($row['features'] ?? []),
                'color'    => $colors[$row['slug']] ?? '#1a56db',
            ];
        }
        return $result;
    }

    /**
     * Retourne tous les plans (gratuit + payants) pour l'interface d'abonnement admin.
     *
     * @return array<string, array{nom:string, mensuel:float, annuel:float, features:array, color:string}>
     */
    public function getAllPlansForUI(): array
    {
        $colors = ['gratuit' => '#64748b', 'basic' => '#1a56db', 'premium' => '#7c3aed'];
        $rows   = $this->where('is_active', 1)->orderBy('ordre')->findAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['slug']] = [
                'nom'      => $row['nom'],
                'mensuel'  => (float) $row['prix_mensuel'],
                'annuel'   => (float) $row['prix_annuel'],
                'features' => is_string($row['features'])
                    ? json_decode($row['features'], true)
                    : ($row['features'] ?? []),
                'color'    => $colors[$row['slug']] ?? '#1a56db',
            ];
        }
        return $result;
    }
}
