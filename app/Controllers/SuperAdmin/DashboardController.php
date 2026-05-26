<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\TenantModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $tenantModel = new TenantModel();
        $userModel   = new UserModel();
        $db          = \Config\Database::connect();

        $stats = [
            'total_tenants'   => $tenantModel->countAll(),
            'active_tenants'  => $tenantModel->where('actif', 1)->countAllResults(),
            'total_users'     => $userModel->countAll(),
            'starter'         => $tenantModel->where('plan', 'starter')->countAllResults(),
            'pro'             => $tenantModel->where('plan', 'pro')->countAllResults(),
            'enterprise'      => $tenantModel->where('plan', 'enterprise')->countAllResults(),
            'expiring_soon'   => $tenantModel->where('actif', 1)
                                             ->where('expire_le >=', date('Y-m-d'))
                                             ->where('expire_le <=', date('Y-m-d', strtotime('+7 days')))
                                             ->countAllResults(),
        ];

        // Revenus mensuels (6 mois) depuis les tenants (simulation basique)
        $tenantsByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $db->table('tenants')
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                ->countAllResults();
            $tenantsByMonth[] = ['label' => date('M Y', strtotime("-$i months")), 'count' => $count];
        }

        $tenants = $tenantModel->orderBy('created_at', 'DESC')->findAll(10);

        return view('superadmin/dashboard', [
            'title'           => lang('SuperAdmin.dashboard_title'),
            'stats'           => $stats,
            'tenants'         => $tenants,
            'tenantsByMonth'  => $tenantsByMonth,
        ]);
    }
}
